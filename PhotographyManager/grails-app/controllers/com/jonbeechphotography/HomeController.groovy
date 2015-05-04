package com.jonbeechphotography

class HomeController
{
    def facebookService

    def login(String token, String current){
        log.error("Authenticating: token: ${token}")
        session.token = token
        redirect(url:current)
    }

    def index(){
        log.info("index token: ${session.token}")
        def model = [loggedIn:session.token != null]

        render(view:"index", model:model)
    }
}
