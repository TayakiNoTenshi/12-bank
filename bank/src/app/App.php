<?php
namespace Bank;
use Bank\Controllers\HomeController;
use Bank\Controllers\AccountController as AC;
use Bank\Messages as M;

class App
{
    const DOMAIN = 'omnicorp.bank.gov';
    const APP = __DIR__ . '/../';
    private static $html;

    public static function start()
    {
        session_name('OmniBank');
        session_start();
        header('Access-Control-Allow-Origin: http://localhost:3000');
        header('Access-Control-Allow-Methods: OPTIONS, GET, POST, DELETE, PUT');
        header("Access-Control-Allow-Headers: Authorization, Content-Type, X-Requested-With");
        header('Access-Control-Allow-Credentials: true');
        // header('Content-Type: application/json');
        //M::init();
        ob_start();
        $uri = explode('/', $_SERVER['REQUEST_URI']);
        array_shift($uri);
        self::route($uri);
        self::$html = ob_get_contents();
        ob_end_clean();
    }

    public static function sent()
    {
        echo self::$html;
    }

    public static function view(string $name, array $data = [])
    {
        $data['loggedIn'] = AC::auth();
        if($data['loggedIn'])
            $data['fullName'] = AC::authName();
        extract($data);
        require __DIR__ .' /../views/'.$name.'.php';
    }

    public static function json(array $data = [])
    {
        header('Content-Type: application/json; charset-utf-8');
        echo json_encode($data);
    }

    public static function redirect(string $url = '')
    {
        header('Location: http://'.self::DOMAIN.'/'.$url, 0);
    }

    private static function route(array $uri)
    {
        $m = $_SERVER['REQUEST_METHOD'];

        //LOGIN

        if ('GET' == $m && count($uri) == 1 && $uri[0] === 'login')
        {
            return (new AC)->showLogin();
        }

        if ('POST' == $m && count($uri) == 1 && $uri[0] === 'login')
        {
            return (new AC)->doLogin();
        }

        if ('POST' == $m && count($uri) == 1 && $uri[0] === 'logout')
        {
            return (new AC)->doLogout();
        }


        if ('POST' == $m && count($uri) == 1 && $uri[0] === 'createAccount')
        {
            (new AC)->createAccount();
        }

        if ('POST' == $m && count($uri) == 1 && $uri[0] === 'deposit')
        {
            (new AC)->deposit();
        }

        if ('POST' == $m && count($uri) == 1 && $uri[0] === 'withdraw')
        {
            (new AC)->withdraw();
        }

        //admin login

        if ('GET' == $m && count($uri) == 1 && $uri[0] === 'adminLogin')
        {
            return (new AC)->showAdminLogin();
        }

        if ('POST' == $m && count($uri) == 1 && $uri[0] === 'aLogin')
        {
            return (new AC)->doAdminLogin();
        }

        if ('GET' == $m && count($uri) == 1 && $uri[0] === 'adminLogout')
        {
            return (new AC)->doAdminLogout();
        }

        if ('POST' == $m && count($uri) == 1 && $uri[0] === 'adminCreate')
        {
            (new AC)->createAdminAccount();
        }

        if ('GET' == $m && count($uri) == 1 && $uri[0] === 'adminCreationForm')
        {
            return (new HomeController)->createAdmin();
        }



        if (count($uri) == 1 && $uri[0] === '')
        {
            return (new HomeController)->index();
        }

        if ('GET' == $m && count($uri) == 1 && $uri[0] === 'json')
        {
            return (new HomeController)->indexJson();
        }

        if ('GET' == $m && count($uri) == 1 && $uri[0] === 'accounts')
        {
            return (new HomeController)->accounts();
        }

        if ('GET' == $m && count($uri) == 1 && $uri[0] === 'accountCreationForm')
        {
            return (new HomeController)->createAccount();
        }

        if ('GET' == $m && count($uri) == 1 && $uri[0] === 'addFunds')
        {
            return (new HomeController)->addFunds();
        }

        if ('GET' == $m && count($uri) == 1 && $uri[0] === 'withdrawFunds')
        {
            return (new HomeController)->withdrawFunds();
        }


        /////////////////////////////////////////////////////////////////////////////////
        ///API
        /////////////////////////////////////////////////////////////////////////////////

        if ('GET' == $m && count($uri) == 2 && $uri[0] === 'api' && $uri[1] === 'messages')
        {
            return (new HomeController)->messagesJson();
        }

        if ('GET' == $m && count($uri) == 2 && $uri[0] === 'api' && $uri[1] === 'acbar')
        {
            return (new HomeController)->acBarJson();
        }

        if ('POST' == $m && count($uri) == 2 && $uri[0] === 'api' && $uri[1] === 'login')
        {
            return (new AC)->doJsonLogin();
        }

        if ('GET' == $m && count($uri) == 2 && $uri[0] === 'api' && $uri[1] === 'logout')
        {
            return (new AC)->doJsonLogout();
        }

        if ('GET' == $m && count($uri) == 2 && $uri[0] === 'api' && $uri[1] === 'accountinfo')
        {
            return (new AC)->getUserDataJson();
        }

        if ('POST' == $m && count($uri) == 2 && $uri[0] === 'api' && $uri[1] === 'deposit')
        {
            return (new AC)->depositJson();
        }

        if ('POST' == $m && count($uri) == 2 && $uri[0] === 'api' && $uri[1] === 'withdraw')
        {
            return (new AC)->withdrawJson();
        }

        if ('GET' == $m && count($uri) == 2 && $uri[0] === 'api' && $uri[1] === 'iban')
        {
            return (new AC)->getIBANjson();
        }

        if ('POST' == $m && count($uri) == 2 && $uri[0] === 'api' && $uri[1] === 'createuser')
        {
            return (new AC)->createAccountJson();
        }

        if ('POST' == $m && count($uri) == 2 && $uri[0] === 'api' && $uri[1] === 'createadmin')
        {
            return self::json(['admin created']);
        }

        if ('POST' == $m && count($uri) == 2 && $uri[0] === 'api' && $uri[1] === 'adminlogin')
        {
            return self::json(['admin logged in']);
        }

        if ('GET' == $m && count($uri) == 2 && $uri[0] === 'api' && $uri[1] === 'adminlogout')
        {
            return self::json(['admin logged out']);
        }

        else
        {
            return (new HomeController)->fourOhFour();
        }
    }
}