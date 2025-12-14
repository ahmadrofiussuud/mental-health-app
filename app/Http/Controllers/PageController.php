<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function page($slug)
    {
        $validPages = [
            'about' => [
                'title' => 'About Us',
                'content' => '<h1>About FlexSport</h1><p>FlexSport is your premier destination for high-performance sports equipment. Founded in 2025, we aim to bridge the gap between amateur enthusiasts and professional-grade gear.</p><p>Our mission is simple: To empower athletes of all levels to reach their full potential through superior equipment and technology.</p>'
            ],
            'faq' => [
                'title' => 'Frequent Questions',
                'content' => '<h1>FAQ</h1><h3>How long does shipping take?</h3><p>Standard shipping takes 3-5 business days.</p><h3>Do you offer international shipping?</h3><p>Yes, we ship globally to over 50 countries.</p>'
            ],
            'shipping' => [
                'title' => 'Shipping Information',
                'content' => '<h1>Shipping Policy</h1><p>We offer free shipping on all orders over Rp 500.000. All orders are processed within 24 hours.</p>'
            ],
            'returns' => [
                'title' => 'Returns & Exchange',
                'content' => '<h1>Return Policy</h1><p>If you are not 100% satisfied with your purchase, you can return the product and get a full refund or exchange the product for another one, be it similar or not.</p>'
            ],
            'contact' => [
                'title' => 'Contact Us',
                'content' => '<h1>Contact Us</h1><p>Email: support@flexsport.com</p><p>Phone: +62 21 555 0123</p><p>Address: Jakarta Sports Tower, Level 15, Jakarta.</p>'
            ],
            'terms' => [
                'title' => 'Terms of Service',
                'content' => '<h1>Terms of Service</h1><p>By using our website, you agree to these terms...</p>'
            ],
            'privacy' => [
                'title' => 'Privacy Policy',
                'content' => '<h1>Privacy Policy</h1><p>Your privacy is important to us. We do not sell your data...</p>'
            ]
        ];

        if (!array_key_exists($slug, $validPages)) {
            abort(404);
        }

        return view('pages.generic', [
            'title' => $validPages[$slug]['title'],
            'content' => $validPages[$slug]['content']
        ]);
    }
}
