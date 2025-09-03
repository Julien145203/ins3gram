<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Liste des messages à afficher à l'utilisateur.
     * @var array
     */
    protected $messages = [];

    /**
     * Instance de session utilisée dans l'application.
     * @var \CodeIgniter\Session\Session|null
     */
    protected $session;

    /**
     * Indique si la session doit démarrer automatiquement.
     * @var bool
     */
    protected $start_session = true;

    /**
     * Titre de la page.
     *
     * @var string
     */
    protected $title = 'Home';

    /**
     * Préfixe ajouté automatiquement au titre de la page.
     *
     * @var string
     */
    protected $title_prefix = 'ins3gram';

    /**
     * Chemin de navigation pour la gestion des breadcrumbs.
     *
     * @var array
     */
    protected $breadcrumb = [];

    protected $menu = 'accueil';

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        if ($this->start_session) {
            $this->session = session();
            if (session()->has('messages')) {
                $this->messages = session()->getFlashdata('messages');
            }
        }
    }

    public function view($vue = null, $datas = [], $admin = true)
    {
        $template_path = $admin ? "templates/admin/" : "templates/front/";
        $flashData = session()->getFlashdata('data');
        if ($flashData) {
            $datas = array_merge($datas, $flashData);
        }
        $headData = [
            'title' => sprintf('%s : %s', $this->title, $this->title_prefix),
            'menus' => $this->loadMenu($admin),
            'template_path' => $template_path,
            'breadcrumb' => $this->breadcrumb,
            'localmenu' => $this->menu,
        ];
        return
            view($template_path . 'head', $headData)
            . (($vue !== null) ? view($vue, $datas) : '')
            . view($template_path . 'footer', ['messages' => $this->messages]);
    }

    protected function loadMenu($admin): array
    {

        $filename = APPPATH . "Config/";
        $filename .= $admin ? "menu_admin.json" : "menu_front.json";

        if (!file_exists($filename)) {
            log_message('error', "Menu JSON file not found: $filename");
            return [];
        }

        $json = file_get_contents($filename);
        $menu = json_decode($json, true);

        if (!is_array($menu)) {
            log_message('error', "Invalid JSON in menu file: $filename");
            return [];
        }

        return $menu;
    }

    public function redirect(string $url, array $data = [])
    {
        // Ajout des messages à la session si présents
        if (!empty($this->messages)) {
            session()->setFlashdata('messages', $this->messages);
        }

        // Ajout des données supplémentaires à la session si présentes
        if (!empty($data)) {
            session()->setFlashdata('data', $data);
        }

        // Redirection avec la méthode CI4
        return redirect()->to(base_url($url));
    }

    /**
     * Ajoute un message de succès.
     *
     * @param string $txt Message à afficher.
     * @return void
     */
    public function success($txt)
    {
        log_message('debug', $txt);
        $this->messages[] = ['txt' => $txt, 'class' => 'alert-success', 'toast' => 'success'];
    }

    /**
     * Ajoute un message informatif.
     *
     * @param string $txt Message à afficher.
     * @return void
     */
    public function message($txt)
    {
        log_message('debug', $txt);
        $this->messages[] = ['txt' => $txt, 'class' => 'alert-info', 'toast' => 'info'];
    }

    /**
     * Ajoute un message d'avertissement.
     *
     * @param string $txt Message à afficher.
     * @return void
     */
    public function warning($txt)
    {
        log_message('debug', $txt);
        $this->messages[] = ['txt' => $txt, 'class' => 'alert-warning', 'toast' => 'warning'];
    }

    /**
     * Ajoute un message d'erreur.
     *
     * @param string $txt Message à afficher.
     * @return void
     */
    public function error($txt)
    {
        log_message('debug', $txt);
        $this->messages[] = ['txt' => $txt, 'class' => 'alert-danger', 'toast' => 'error'];
    }

    /**
     * Ajoute un élément au fil d'Ariane.
     *
     * @param string $text Texte de l'élément.
     * @param string|array $url URL ou segments de l'élément.
     * @param string $info Informations supplémentaires.
     * @return void
     */
    protected function addBreadcrumb($text, $url, $info = '')
    {
        if ($this->breadcrumb === null) {
            $this->breadcrumb = [];
        }
        $this->breadcrumb[] = [
            'text' => $text,
            'url' => (is_array($url) ? '/' . implode('/', $url) : $url),
            'info' => $info,
        ];
    }
}
