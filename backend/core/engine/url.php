<?php

/*
 * This file is part of Fork CMS.
 *
 * For the full copyright and license information, please view the license
 * file that was distributed with this source code.
 */

/**
 * This class will handle the incoming URL.
 *
 * @author Tijs Verkoyen <tijs@sumocoders.be>
 */
class BackendURL extends BackendBaseObject
{
	/**
	 * The host, will be used for cookies
	 *
	 * @var	string
	 */
	private $host;

	/**
	 * The querystring
	 *
	 * @var	string
	 */
	private $queryString;

	public function __construct()
	{
		// add to registry
		Spoon::set('url', $this);

		$this->setQueryString($_SERVER['REQUEST_URI']);
		$this->setHost($_SERVER['HTTP_HOST']);
		$this->processQueryString();
	}

	/**
	 * Get the domain
	 *
	 * @return string The current domain (without www.)
	 */
	public function getDomain()
	{
		// get host
		$host = $this->getHost();

		// replace
		return str_replace('www.', '', $host);
	}

	/**
	 * Get the host
	 *
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * Get the full querystring
	 *
	 * @return string
	 */
	public function getQueryString()
	{
		return $this->queryString;
	}

	/**
	 * Process the querystring
	 */
	private function processQueryString()
	{
		// store the querystring local, so we don't alter it.
		$queryString = $this->getQueryString();

		// find the position of ? (which separates real URL and GET-parameters)
		$positionQuestionMark = strpos($queryString, '?');

		// separate the GET-chunk from the parameters
		$getParameters = '';
		if($positionQuestionMark === false) $processedQueryString = $queryString;
		else
		{
			$processedQueryString = substr($queryString, 0, $positionQuestionMark);
			$getParameters = substr($queryString, $positionQuestionMark);
		}

		// split into chunks, a Backend URL will always look like /<lang>/<module>/<action>(?GET)
		$chunks = (array) explode('/', trim($processedQueryString, '/'));

		// check if this is a request for a AJAX-file
		$isAJAX = (isset($chunks[1]) && $chunks[1] == 'ajax.php');

		// get the language, this will always be in front
		$language = '';
		if(isset($chunks[1]) && $chunks[1] != '')
		{
			$language = SpoonFilter::getValue($chunks[1], array_keys(BackendLanguage::getWorkingLanguages()), '');
		}

		// no language provided?
		if($language == '' && !$isAJAX)
		{
			// remove first element
			array_shift($chunks);

			// redirect to login
			SpoonHTTP::redirect('/' . NAMED_APPLICATION . '/' . SITE_DEFAULT_LANGUAGE . '/' . implode('/', $chunks) . $getParameters);
		}

		// get the module, null will be the default
		$module = (isset($chunks[2]) && $chunks[2] != '') ? $chunks[2] : 'dashboard';

		// get the requested action, if it is passed
		if(isset($chunks[3]) && $chunks[3] != '') $action = $chunks[3];

		// no action passed through URL
		elseif(!$isAJAX)
		{
			// check if module path is not yet defined
			if(!defined('BACKEND_MODULE_PATH'))
			{
				// build path for core
				if($module == 'core') define('BACKEND_MODULE_PATH', BACKEND_PATH . '/' . $module);

				// build path to the module and define it. This is a constant because we can use this in templates.
				else define('BACKEND_MODULE_PATH', BACKEND_MODULES_PATH . '/' . $module);
			}

			/*
			 * check if the config is present? If it isn't present there is a huge problem, so we
			 * will stop our code by throwing an error
			 */
			if(!SpoonFile::exists(BACKEND_MODULE_PATH . '/config.php'))
			{
				// in debug mode we want to see the error
				if(SPOON_DEBUG) throw new BackendException('The configfile for the module (' . $module . ') can\'t be found.');

				else
				{
					// @todo	don't use redirects for error, we should have something like an invoke method.

					// build the url
					$errorUrl = '/' . NAMED_APPLICATION . '/' . $language . '/error?type=action-not-allowed';

					// add the querystring, it will be processed by the error-handler
					$errorUrl .= '&querystring=' . urlencode('/' . $this->getQueryString());

					// redirect to the error page
					SpoonHTTP::redirect($errorUrl, 307);
				}
			}

			// build config-object-name
			$configClassName = 'Backend' . SpoonFilter::toCamelCase($module . '_config');

			// require the config file, we validated before for existence.
			require_once BACKEND_MODULE_PATH . '/config.php';

			// validate if class exists (aka has correct name)
			if(!class_exists($configClassName))
			{
				throw new BackendException(
					'The config file is present, but the classname should be: ' . $configClassName . '.'
				);
			}

			// create config-object, the constructor will do some magic
			$config = new $configClassName($module);

			// set action
			$action = ($config->getDefaultAction() !== null) ? $config->getDefaultAction() : 'index';
		}

		// AJAX parameters are passed via GET or POST
		if($isAJAX)
		{
			$module = (isset($_GET['fork']['module'])) ? $_GET['fork']['module'] : '';
			$action = (isset($_GET['fork']['action'])) ? $_GET['fork']['action'] : '';
			$language = (isset($_GET['fork']['language'])) ? $_GET['fork']['language'] : SITE_DEFAULT_LANGUAGE;
			$module = (isset($_POST['fork']['module'])) ? $_POST['fork']['module'] : $module;
			$action = (isset($_POST['fork']['action'])) ? $_POST['fork']['action'] : $action;
			$language = (isset($_POST['fork']['language'])) ? $_POST['fork']['language'] : $language;

			$this->setModule($module);
			$this->setAction($action);
			BackendLanguage::setWorkingLanguage($language);
		}

		// regular request
		else $this->processRegularRequest($module, $action, $language);
	}

	/**
	 * Process a regular request
	 *
	 * @param string $module The requested module.
	 * @param string $action The requested action.
	 * @param string $language The requested language.
	 */
	private function processRegularRequest($module, $action, $language)
	{
		// the person isn't logged in? or the module doesn't require authentication
		if(!BackendAuthentication::isLoggedIn() && !BackendAuthentication::isAllowedModule($module))
		{
			// redirect to login
			SpoonHTTP::redirect('/' . NAMED_APPLICATION . '/' . $language . '/authentication/?querystring=' . urlencode('/' . $this->getQueryString()));
		}

		// the person is logged in
		else
		{
			// does our user has access to this module?
			if(!BackendAuthentication::isAllowedModule($module))
			{
				// if the module is the dashboard redirect to the first allowed module
				if($module == 'dashboard')
				{
					// require navigation-file
					require_once BACKEND_CACHE_PATH . '/navigation/navigation.php';

					// loop the navigation to find the first allowed module
					foreach($navigation as $key => $value)
					{
						// split up chunks
						list($module, $action) = explode('/', $value['url']);

						// user allowed?
						if(BackendAuthentication::isAllowedModule($module))
						{
							// redirect to the page
							SpoonHTTP::redirect('/' . NAMED_APPLICATION . '/' . $language . '/' . $value['url']);
						}
					}
				}
				// the user doesn't have access, redirect to error page
				SpoonHTTP::redirect('/' . NAMED_APPLICATION . '/' . $language . '/error?type=module-not-allowed&querystring=' . urlencode('/' . $this->getQueryString()), 307);
			}

			// we have access
			else
			{
				// can our user execute the requested action?
				if(!BackendAuthentication::isAllowedAction($action, $module))
				{
					// the user hasn't access, redirect to error page
					SpoonHTTP::redirect('/' . NAMED_APPLICATION . '/' . $language . '/error?type=action-not-allowed&querystring=' . urlencode('/' . $this->getQueryString()), 307);
				}

				// let's do it
				else
				{
					// set the working language, this is not the interface language
					BackendLanguage::setWorkingLanguage($language);

					$this->setLocale();
					$this->setModule($module);
					$this->setAction($action);
				}
			}
		}
	}

	/**
	 * Set the host
	 *
	 * @param string $host The host.
	 */
	private function setHost($host)
	{
		$this->host = (string) $host;
	}

	/**
	 * Set the locale
	 */
	private function setLocale()
	{
		$default = BackendModel::getModuleSetting('core', 'default_interface_language');
		$locale = $default;
		$possibleLocale = array_keys(BackendLanguage::getInterfaceLanguages());

		// is the user authenticated
		if(BackendAuthentication::getUser()->isAuthenticated())
		{
			$locale = BackendAuthentication::getUser()->getSetting('interface_language', $default);
		}

		// no authenticated user, but available from a cookie
		elseif(CommonCookie::exists('interface_language'))
		{
			$locale = CommonCookie::get('interface_language');
		}

		// validate if the requested locale is possible
		if(!in_array($locale, $possibleLocale)) $locale = $default;

		BackendLanguage::setLocale($locale);
	}

	/**
	 * Set the querystring
	 *
	 * @param string $queryString The full query-string.
	 */
	private function setQueryString($queryString)
	{
		$queryString = trim((string) $queryString, '/');

		// replace GET with encoded GET in the queryString to prevent XSS
		if(isset($_GET) && !empty($_GET))
		{
			// strip GET from the queryString
			list($queryString) = explode('?', $queryString, 2);

			// readd
			$queryString = $queryString . '?' . http_build_query($_GET);
		}

		$this->queryString = $queryString;
	}
}
