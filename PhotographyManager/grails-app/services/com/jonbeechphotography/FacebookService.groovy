package com.jonbeechphotography

class FacebookService
{
    private static final String BASE_URL = "https://graph.facebook.com/v2.3/"

    public getAuth(){
        def responce = rest.get(BASE_URL + "me")
        responce.json
    }
}
