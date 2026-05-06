declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmPasswordController extends Controller
{
    public function showConfirmForm(): View
    {
        return view('auth.confirm-password');
    }

    public function confirm(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if (! Auth::guard('web')->validate(['email' => Auth::user()->email, 'password' => $request->password])) {
            throw ValidationException::withMessages(['password' => ['The provided password is incorrect.']]);
        }

        return redirect()->route('dashboard')->with('success', 'Password confirmed successfully.');
    }
}
