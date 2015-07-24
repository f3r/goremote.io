<?php
namespace GoRemote\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TwitterController
{
    public function authAction(Request $request, Application $app)
    {
		if ($app['session']->get('twitter_oauth_token') === null) {
			// get the request token
			$reply = $app['twitter']->oauth_requestToken([
				'oauth_callback' => $request->getUri()
			]);

			if (empty($reply)) {
				return new Response('Reply is empty');
			}

			// store the token
			$app['twitter']->setToken($reply->oauth_token, $reply->oauth_token_secret);

			$app['session']->set['twitter_oauth_token'] = $reply->oauth_token;
			$app['session']->set['twitter_oauth_token_secret'] = $reply->oauth_token_secret;

			// redirect to auth website
			$auth_url = $app['twitter']->oauth_authorize();
			return $app->redirect($auth_url);
		} 
        return new Response('oops');
    }
}
