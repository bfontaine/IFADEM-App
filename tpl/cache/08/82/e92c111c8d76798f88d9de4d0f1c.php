<?php

/* main.html */
class __TwigTemplate_0882e92c111c8d76798f88d9de4d0f1c extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!doctype html>
<html lang=\"";
        // line 2
        echo twig_escape_filter($this->env, ((array_key_exists("lang", $context)) ? (_twig_default_filter($this->getContext($context, "lang"), "fr")) : ("fr")), "html", null, true);
        echo "\" dir=\"";
        echo twig_escape_filter($this->env, ((array_key_exists("dir", $context)) ? (_twig_default_filter($this->getContext($context, "dir"), "ltr")) : ("ltr")), "html", null, true);
        echo "\">
    <head>
        <meta charset=\"utf-8\" />
        <meta name=\"language\" content=\"";
        // line 5
        echo twig_escape_filter($this->env, ((array_key_exists("lang", $context)) ? (_twig_default_filter($this->getContext($context, "lang"), "fr")) : ("fr")), "html", null, true);
        echo "\" />

        <title>";
        // line 7
        echo twig_escape_filter($this->env, ((array_key_exists("main_title", $context)) ? (_twig_default_filter($this->getContext($context, "main_title"), "IFADEM")) : ("IFADEM")), "html", null, true);
        echo "</title>

        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
        <link rel=\"stylesheet\" href=\"js/jquery.mobile-1.3.1.min.css\" />
        <script src=\"js/jquery-1.9.1.min.js\"></script>
        <script src=\"js/jquery.mobile-1.3.1.min.js\"></script>

        ";
        // line 15
        echo "        <link rel=\"icon\" type=\"image/png\"        href=\"imgs/ifadem-logo.png\" />
        <link rel=\"apple-touch-icon\"             href=\"imgs/ifadem-logo.png\" />
        <link rel=\"apple-touch-icon-precomposed\" href=\"imgs/ifadem-logo.png\" />
    </head>
    <body>
        <script type=\"data/json\" id=\"server-data\">";
        // line 20
        echo twig_escape_filter($this->env, ((array_key_exists("json_data", $context)) ? (_twig_default_filter($this->getContext($context, "json_data"), "{}")) : ("{}")), "html", null, true);
        echo "</script>

        <div data-role=\"page\" id=\"home\">
            <div data-role=\"header\">
                ";
        // line 27
        echo "                <a href=\"#\" data-role=\"button\"
                            data-icon=\"delete\"
                            data-iconpos=\"notext\"
                            id=\"cancel-selection\">";
        // line 30
        echo twig_escape_filter($this->env, (($this->getAttribute($this->getContext($context, "buttons", true), "check", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getContext($context, "buttons", true), "check"), "Annuler")) : ("Annuler")), "html", null, true);
        echo "</a>
                        
                <h1>";
        // line 32
        echo twig_escape_filter($this->env, ((array_key_exists("title", $context)) ? (_twig_default_filter($this->getContext($context, "title"), "IFADEM")) : ("IFADEM")), "html", null, true);
        echo "</h1>

                <a href=\"#\" data-role=\"button\"
                            data-icon=\"check\"
                            data-iconpos=\"notext\"
                            id=\"select-els\">";
        // line 37
        echo twig_escape_filter($this->env, (($this->getAttribute($this->getContext($context, "buttons", true), "check", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getContext($context, "buttons", true), "check"), "OK")) : ("OK")), "html", null, true);
        echo "</a>

            </div>
            <div data-role=\"content\">
                <div data-role=\"collapsible-set\">
                    ";
        // line 42
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getContext($context, "countries"));
        foreach ($context['_seq'] as $context["_key"] => $context["country"]) {
            // line 43
            echo "                    <div data-role=\"collapsible\">
                        <h2>";
            // line 44
            echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "country"), "name"), "html", null, true);
            echo "</h2>
                        <ul data-role=\"listview\">
                            ";
            // line 46
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "country"), "contents"));
            foreach ($context['_seq'] as $context["_key"] => $context["content"]) {
                // line 47
                echo "                            <li data-content-id=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "content"), "id"), "html", null, true);
                echo "\" class=\"content\">
                                <img src=\"";
                // line 48
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "content"), "thumbnail"), "html", null, true);
                echo "\" class=\"content-thumbnail\" />
                                <h3>";
                // line 49
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "content"), "title"), "html", null, true);
                echo "</h3>
                                <p>";
                // line 50
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "content"), "description"), "html", null, true);
                echo "</p>
                                <input type=\"checkbox\" ";
                // line 51
                if ($this->getAttribute($this->getContext($context, "content"), "selected")) {
                    echo "checked=\"checked\"";
                }
                echo " />
                                <p class=\"ui-li-aside\">";
                // line 52
                echo twig_escape_filter($this->env, $this->getAttribute($this->getContext($context, "content"), "size"), "html", null, true);
                echo "</p>
                            </li>
                            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['content'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 55
            echo "                        </ul>
                    </div>
                    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['country'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
        // line 58
        echo "                </div>
            </div>
        </div>

        ";
        // line 71
        echo "
    </body>
</html>
";
    }

    public function getTemplateName()
    {
        return "main.html";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  145 => 71,  139 => 58,  131 => 55,  122 => 52,  116 => 51,  112 => 50,  108 => 49,  104 => 48,  99 => 47,  95 => 46,  90 => 44,  87 => 43,  83 => 42,  75 => 37,  67 => 32,  62 => 30,  57 => 27,  50 => 20,  43 => 15,  33 => 7,  28 => 5,  20 => 2,  17 => 1,);
    }
}
