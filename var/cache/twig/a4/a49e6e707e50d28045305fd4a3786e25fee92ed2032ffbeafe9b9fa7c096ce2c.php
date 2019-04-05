<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* base.html.twig */
class __TwigTemplate_84cd13294e42c14b7d40e8165432a63773ab2057537db4e4b29ff441f4a5733b extends \Twig\Template
{
    private $source;

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'title' => [$this, 'block_title'],
            'stylesheets' => [$this, 'block_stylesheets'],
            'body' => [$this, 'block_body'],
            'javascripts' => [$this, 'block_javascripts'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"UTF-8\">
        <title>";
        // line 5
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        ";
        // line 6
        $this->displayBlock('stylesheets', $context, $blocks);
        // line 18
        echo "    </head>
    <body>
        ";
        // line 20
        $this->displayBlock('body', $context, $blocks);
        // line 21
        echo "        ";
        $this->displayBlock('javascripts', $context, $blocks);
        // line 22
        echo "    </body>
</html>
";
    }

    // line 5
    public function block_title($context, array $blocks = [])
    {
        echo "Welcome!";
    }

    // line 6
    public function block_stylesheets($context, array $blocks = [])
    {
        // line 7
        echo "<LINK rel=\"stylesheet\" type=\"text/css\" HREF=\"/styles/base_style.css\">
<style>
    html {
        font-size: 62.5%;
    }

    body {
        font-size: 1.6rem;
    }
</style>
        ";
    }

    // line 20
    public function block_body($context, array $blocks = [])
    {
    }

    // line 21
    public function block_javascripts($context, array $blocks = [])
    {
    }

    public function getTemplateName()
    {
        return "base.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  94 => 21,  89 => 20,  75 => 7,  72 => 6,  66 => 5,  60 => 22,  57 => 21,  55 => 20,  51 => 18,  49 => 6,  45 => 5,  39 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("<!DOCTYPE html>
<html>
    <head>
        <meta charset=\"UTF-8\">
        <title>{% block title %}Welcome!{% endblock %}</title>
        {% block stylesheets %}
<LINK rel=\"stylesheet\" type=\"text/css\" HREF=\"/styles/base_style.css\">
<style>
    html {
        font-size: 62.5%;
    }

    body {
        font-size: 1.6rem;
    }
</style>
        {% endblock %}
    </head>
    <body>
        {% block body %}{% endblock %}
        {% block javascripts %}{% endblock %}
    </body>
</html>
", "base.html.twig", "C:\\Users\\Chris Haas.VENDI\\Development\\base\\templates\\base.html.twig");
    }
}
