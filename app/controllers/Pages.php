<?php 

/**
 * Pages Controller
 * Handles requests related to Pages.
 */
class Pages extends Controller
{
    private $postModel;

    public function __construct()
    {
        // Load the Post model
         
    }

    // Default method
    public function index()
    {
         
         

        $data = [
            'title' => 'Share Posts',
            'description' => 'This is a simple PHP MVC framework.'    
        ];

        // Load the index view
        $this->view('pages/index', $data);
    }

    // About page method
    public function about()
    {
        $data = [
            'title' => 'About Us',
            'description' => 'SharePosts With Other Users.'
        ];

        // Load the about view
        $this->view('pages/about', $data);
    }
}
