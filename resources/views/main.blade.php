 <!DOCTYPE html>
  <html>
    <head>
        <!-- CSS  -->
    {!! Html::style('//fonts.googleapis.com/icon?family=Material+Icons') !!}
    {!! Html::style('materialize/css/materialize.min.css')!!} 
    {!! Html::style('css/style.css') !!}

      <!--Let browser know website is optimized for mobile-->
      <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>

    <body>
        
        <nav>
          <div class="nav-wrapper">
            <a href="#" class="brand-logo">Logo</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
              <li><a href="sass.html">Sass</a></li>
              <li><a href="badges.html">Components</a></li>
              <li><a href="collapsible.html">JavaScript</a></li>
            </ul>
          </div>
        </nav>

    <!--  Scripts-->
    {!! Html::script('js/jquery-2.1.1.min.js') !!}
    {!! Html::script('js/init.js') !!}
    {!! Html::script('materialize-css/js/materialize.min.js')!!}
    </body>
  </html>