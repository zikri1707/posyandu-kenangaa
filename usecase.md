```mermaid
usecaseDiagram
    actor SuperAdmin as "Super Admin"
    actor Admin as "Admin"

    rectangle AdminDashboard {
        usecase "Manage Admin" as UC1
        usecase "Manage Patients" as UC2
        usecase "Manage Posyandu" as UC3
        usecase "Manage Medical Records" as UC4
        usecase "Manage Articles" as UC5
        usecase "Manage Schedules" as UC6
        usecase "Manage Gallery" as UC7

        SuperAdmin -right-> UC1
        SuperAdmin --> UC2
        SuperAdmin --> UC3
        SuperAdmin --> UC4
        SuperAdmin --> UC5
        SuperAdmin --> UC6
        SuperAdmin --> UC7

        Admin -left-> UC2
        Admin --> UC3
        Admin --> UC4
        Admin --> UC5
        Admin --> UC6
        Admin --> UC7
    }

    note right of SuperAdmin
        Hak akses penuh:
        - CRUD Admin
        - Full kontrol semua modul
    end note

    note left of Admin
        Hak akses terbatas:
        - Tidak bisa manage Admin
        - Akses terbatas untuk modul lainnya
    end note
```
