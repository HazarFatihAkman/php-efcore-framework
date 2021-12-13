<?php
namespace View\Components;

class Status {
    public function Success(){
        $html = '<!DOCTYPE html>
        <html>
          <head>
          <title>Başarılı</title>
          <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
          <meta name="description" content="Hata">
          <meta name="keywords" content="Hata">
          <meta name="author" content="Arul John">
          <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" media="screen" rel="stylesheet">
          </head>
        <body>
        <main role="main" style="margin-top:10%;">
          <div class="container">
            <div class="row"><div class="text-center">
            <img src="https://www.flaticon.com/svg/vstatic/svg/845/845646.svg?token=exp=1610673688~hmac=a6e5244ebcaec6639bd8da60fe720548" style="height:75px;width:75px;" alt="flaticon">
          </div>
              <h1 class="text-center">Successful.</h1>
              <h1 class="text-center">You are being redirected.</h1> 
            </div>
          </div>
        </main>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        </body>
        </html>';
        return $html;
    }

    public function Failed(){
        $html = '<!DOCTYPE html>
        <html>
          <head>
          <title>Hata</title>
          <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
          <meta name="description" content="Hata">
          <meta name="keywords" content="Hata">
          <meta name="author" content="Arul John">
          <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" media="screen" rel="stylesheet">
          </head>
        <body>
        <main role="main" style="margin-top:10%;">
          <div class="container">
            <div class="row"><div class="text-center">
            <img src="https://www.flaticon.com/svg/vstatic/svg/1277/1277613.svg?token=exp=1610673280~hmac=06e9d8fa88a690814ca71db935cf4edf" style="height:75px;width:75px;" alt="flaticon">
          </div>
              <h1 class="text-center">Not successful.</h1>
              <h1 class="text-center">Pls, call the technical team or check inputs should correct.</h1>
              <h1 class="text-center">You are being redirected.</h1> 
            </div>
          </div>
        </main>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        </body>
        </html>';
        return $html;
    }
}