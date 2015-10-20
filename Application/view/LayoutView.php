<?php


class LayoutView {
  
  //echos out all of the html
  public function render($isLoggedIn, LoginView $v, $rv) {
    $html = '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <link rel="stylesheet" type="text/css" href="CSS/style.css" />
          <title>A shitty Monday</title>
        </head>
        <body>
          <h1>Php project - lw222gz</h1>
          ' . $this->renderIsLoggedIn($isLoggedIn) . '
          
          <div class="container">';
          
          if (!isset($_GET["register"])){
              $html .= $v->response();
          }
          else{
            $html .= $rv -> RegisterLayout();
          }
          $html .= '</div>
         </body>
      </html>
    ';
    echo $html;
  }
  
  private function renderIsLoggedIn($isLoggedIn) {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    }
    else {
      return $this->renderOption() . '<br/><h2>Not logged in</h2>';
    }
  }
  
  private function renderOption(){
      if (isset($_GET["register"])){
           return '<a href=?>Back to login</a>';
      }
      else { 
        return '<a href=?register>Register a new user</a>';
      }
    }
  
}
