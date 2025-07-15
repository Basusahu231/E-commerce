<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CMSController extends Controller
{
    public function getPage($page)
    {
        // Dummy content (replace with DB logic if needed)
        $pages = [
            'about-us' => 'Welcome to our About Us page.',
            'privacy-policy' => 'This is our Privacy Policy.',
            'terms' => 'These are our Terms and Conditions.',
        ];

        if (!isset($pages[$page])) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'slug' => $page,
            'content' => $pages[$page],
        ]);
    }
}
