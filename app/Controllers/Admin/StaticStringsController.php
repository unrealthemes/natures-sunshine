<?php

namespace App\Controllers\Admin;

class StaticStringsController
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'registerAdminPage']);
        add_action('admin_init', [$this, 'updateStrings']);
    }
    
    public function registerAdminPage()
    {
        add_submenu_page(
            'tools.php',
            __('Static strings editor'),
            __('Static strings editor'),
            'manage_options',
            'static-strings-editor',
            [$this, 'view']
        );
    }
    
    public function view()
    {
        get_template_part('template-parts/admin/static-strings-editor');
    }
    
    public static function getStrings()
    {
        return require get_template_directory() . '/app/Data/Strings.php';
    }
    
    public function updateStrings()
    {
        if ( ! ( isset($_GET['strings-update']) && isset($_POST['keys']) && isset($_POST['values']) )) {
            return;
        }
        
        $data = array_combine($_POST['keys'], $_POST['values']);
        
        file_put_contents(get_template_directory() . '/app/Data/Strings.php', "<?php\n\n" . 'return ' . var_export($data, true) . ";\n");
    }
    
}