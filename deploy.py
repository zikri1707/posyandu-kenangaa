import os
import zipfile
import ftplib
import shutil
import sys

# Configuration
FTP_HOST = "ftpupload.net"
FTP_USER = "if0_42306273"
FTP_PASS = "WeHJgzevsNUXbg"

DB_HOST = "sql213.infinityfree.com"
DB_NAME = "if0_42306273_XXX" # User should replace XXX with their actual DB name suffix if different
DB_USER = "if0_42306273"
DB_PASS = "WeHJgzevsNUXbg"

TEMP_ZIP = "deploy.zip"

def create_htaccess():
    content = """# Disable directory listing
Options -Indexes

# Block access to sensitive Laravel folders and files
RedirectMatch 404 ^/\\.env
RedirectMatch 404 ^/app/
RedirectMatch 404 ^/bootstrap/
RedirectMatch 404 ^/config/
RedirectMatch 404 ^/database/
RedirectMatch 404 ^/resources/
RedirectMatch 404 ^/routes/
RedirectMatch 404 ^/tests/
RedirectMatch 404 ^/vendor/
RedirectMatch 404 ^/composer\\.(json|lock)
RedirectMatch 404 ^/package\\.(json|lock)
RedirectMatch 404 ^/vite\\.config\\.js
RedirectMatch 404 ^/artisan
RedirectMatch 404 ^/Dockerfile
RedirectMatch 404 ^/render\\.yaml

<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^$ public/ [L]
    RewriteRule (.*) public/$1 [L]
</IfModule>
"""
    with open(".htaccess", "w", newline="\n") as f:
        f.write(content)
    print("Created .htaccess file")

def create_unzip_script():
    content = """<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(600);

echo "<h3>Extracting deploy.zip...</h3>";
if (!file_exists('deploy.zip')) {
    die("<p style='color: red;'>Error: deploy.zip not found on server.</p>");
}

$zip = new ZipArchive;
if ($zip->open('deploy.zip') === TRUE) {
    $zip->extractTo(__DIR__);
    $zip->close();
    echo "<p style='color: green;'>Success! Extraction complete.</p>";
    unlink('deploy.zip');
    echo "<p>Temporary zip file deleted.</p>";
} else {
    echo "<p style='color: red;'>Failed to open deploy.zip</p>";
}
?>
"""
    with open("unzip.php", "w", newline="\n") as f:
        f.write(content)
    print("Created unzip.php helper script")

def create_db_import_script():
    content = f"""<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(600);

$host = '{DB_HOST}';
$db   = '{DB_NAME}';
$user = '{DB_USER}';
$pass = '{DB_PASS}';
$file = __DIR__ . '/database/db_snapshot.sql';

echo "<h3>Importing Database Snapshot...</h3>";

if (!file_exists($file)) {{
    die("<p style='color: red;'>Error: file 'database/db_snapshot.sql' not found. Make sure you ran db-export.bat before deploying.</p>");
}}

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {{
    die("<p style='color: red;'>Connection failed: " . $conn->connect_error . "</p>");
}}

$sql = file_get_contents($file);

if ($conn->multi_query($sql)) {{
    do {{
        if ($result = $conn->store_result()) {{
            $result->free();
        }}
    }} while ($conn->next_result());
    
    echo "<p style='color: green;'>Success! Database snapshot imported successfully.</p>";
}} else {{
    echo "<p style='color: red;'>Error importing database: " . $conn->error . "</p>";
}}
$conn->close();
?>
"""
    with open("db_import.php", "w", newline="\n") as f:
        f.write(content)
    print("Created db_import.php database helper script")

def create_production_env():
    # Read local .env
    env_lines = []
    if os.path.exists(".env"):
        with open(".env", "r") as f:
            env_lines = f.readlines()
            
    prod_lines = []
    skip_keys = ["APP_ENV", "APP_DEBUG", "DB_CONNECTION", "DB_HOST", "DB_PORT", "DB_DATABASE", "DB_USERNAME", "DB_PASSWORD", "SESSION_DRIVER", "SESSION_SECURE_COOKIE"]
    
    for line in env_lines:
        skip = False
        for key in skip_keys:
            if line.startswith(key + "="):
                skip = True
                break
        if not skip:
            prod_lines.append(line)
            
    # Append production configurations
    prod_lines.append("APP_ENV=production\n")
    prod_lines.append("APP_DEBUG=true\n") # Set true for easy debugging initially
    prod_lines.append("DB_CONNECTION=mysql\n")
    prod_lines.append(f"DB_HOST={DB_HOST}\n")
    prod_lines.append("DB_PORT=3306\n")
    prod_lines.append(f"DB_DATABASE={DB_NAME}\n")
    prod_lines.append(f"DB_USERNAME={DB_USER}\n")
    prod_lines.append(f"DB_PASSWORD={DB_PASS}\n")
    prod_lines.append("SESSION_DRIVER=file\n")
    prod_lines.append("SESSION_SECURE_COOKIE=false\n")
    prod_lines.append("SESSION_SAME_SITE=lax\n")
    
    with open(".env.production.temp", "w", newline="\n") as f:
        f.writelines(prod_lines)
    print("Created temporary production .env file")

def zip_project():
    print("Packaging project into deploy.zip (this might take a minute)...")
    exclude_dirs = [".git", "node_modules", "tests", "storage/logs", "storage/framework/cache", "storage/framework/sessions", "storage/framework/views"]
    exclude_files = ["cloudflared.exe", "start-cloudflare-tunnel.bat", "start-localtunnel.bat", "db-export.bat", "db-import.bat", "deploy.py", "deploy.zip", ".env"]
    
    zipf = zipfile.ZipFile(TEMP_ZIP, 'w', zipfile.ZIP_DEFLATED)
    
    for root, dirs, files in os.walk("."):
        # Modify dirs in-place to skip excluded directories
        dirs[:] = [d for d in dirs if not any(os.path.normpath(os.path.join(root, d)).replace("\\", "/").endswith(ex) for ex in exclude_dirs)]
        
        for file in files:
            file_path = os.path.join(root, file)
            norm_path = os.path.normpath(file_path).replace("\\", "/")
            
            # Skip excluded files
            if any(norm_path.endswith(ex) or file == ex for ex in exclude_files):
                continue
                
            # If it's our temporary production .env, write it as '.env' in the zip
            if file == ".env.production.temp":
                zipf.write(file_path, ".env")
            else:
                zipf.write(file_path)
                
    zipf.close()
    print("Zip file created successfully")

def upload_ftp():
    print(f"Connecting to FTP server {FTP_HOST}...")
    ftp = ftplib.FTP(FTP_HOST)
    ftp.login(user=FTP_USER, passwd=FTP_PASS)
    print("Logged in successfully.")
    
    # Move to htdocs
    ftp.cwd("htdocs")
    
    # Upload scripts
    for file in ["unzip.php", "db_import.php", TEMP_ZIP]:
        if os.path.exists(file):
            print(f"Uploading {file}...")
            with open(file, "rb") as f:
                ftp.storbinary(f"STOR {file}", f)
            print(f"{file} uploaded.")
            
    ftp.quit()
    print("FTP upload completed and connection closed.")

def cleanup():
    print("Cleaning up temporary local files...")
    for file in [".htaccess", "unzip.php", "db_import.php", TEMP_ZIP, ".env.production.temp"]:
        if os.path.exists(file):
            os.remove(file)
    print("Cleanup done.")

if __name__ == "__main__":
    try:
        # Check if DB_NAME was customized
        if DB_NAME.endswith("XXX"):
            print("WARNING: MySQL Database Name still ends in 'XXX'.")
            db_suffix = input("Masukkan suffix nama database Anda (contoh: posyandu jika nama DB Anda if0_42306273_posyandu): ")
            if db_suffix:
                DB_NAME = f"if0_42306273_{db_suffix}"
                # Update script content variables
                print(f"Menggunakan nama database: {DB_NAME}")
        
        # 1. Export database first to make sure snapshot is updated
        print("Mengekspor database lokal terlebih dahulu...")
        os.system("db-export.bat")
        
        # 2. Build deployment files
        create_htaccess()
        create_unzip_script()
        create_db_import_script()
        create_production_env()
        
        # 3. Zip and upload
        zip_project()
        upload_ftp()
        
    except Exception as e:
        print(f"An error occurred: {e}")
    finally:
        cleanup()
        print("\n=== SELESAI ===")
        print("Langkah selanjutnya:")
        print("1. Buka browser Anda dan kunjungi halaman ekstrak:")
        print("   http://<domain-anda.great-site.net>/unzip.php")
        print("2. Setelah sukses ekstrak, jalankan import database:")
        print("   http://<domain-anda.great-site.net>/db_import.php")
        print("3. Buka halaman utama website Anda!")
