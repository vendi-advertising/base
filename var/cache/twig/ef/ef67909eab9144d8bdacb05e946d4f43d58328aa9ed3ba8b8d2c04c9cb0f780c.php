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

/* setup/index.html.twig */
class __TwigTemplate_397c0c84d43c7ed9362e6ed3d35d2e879c5609c4c93475324c6cd3a2d87a0dee extends \Twig\Template
{
    private $source;

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        // line 1
        $this->parent = $this->loadTemplate("base.html.twig", "setup/index.html.twig", 1);
        $this->blocks = [
            'body' => [$this, 'block_body'],
        ];
    }

    protected function doGetParent(array $context)
    {
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_body($context, array $blocks = [])
    {
        // line 4
        echo "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
    <tr>
      <td class=\"mainheader\">&nbsp;</td>
      <td class=\"mainheadertitle\">
         Basic Analysis and Security Engine (BASE) Setup Program
      </td>
    </tr>
</table>

    ";
        // line 13
        if ((isset($context["errorMsg"]) || array_key_exists("errorMsg", $context))) {
            // line 14
            echo "        <div class=\"errorMsg\" align=\"center\">
            ";
            // line 15
            echo twig_escape_filter($this->env, (isset($context["errorMsg"]) || array_key_exists("errorMsg", $context) ? $context["errorMsg"] : (function () { throw new RuntimeError('Variable "errorMsg" does not exist.', 15, $this->source); })()), "html", null, true);
            echo "
        </div>
    ";
        }
        // line 18
        echo "
    ";
        // line 19
        echo (isset($context["body"]) || array_key_exists("body", $context) ? $context["body"] : (function () { throw new RuntimeError('Variable "body" does not exist.', 19, $this->source); })());
        echo "
";
    }

    public function getTemplateName()
    {
        return "setup/index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  72 => 19,  69 => 18,  63 => 15,  60 => 14,  58 => 13,  47 => 4,  44 => 3,  27 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("{% extends 'base.html.twig' %}

{% block body %}
<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"5\">
    <tr>
      <td class=\"mainheader\">&nbsp;</td>
      <td class=\"mainheadertitle\">
         Basic Analysis and Security Engine (BASE) Setup Program
      </td>
    </tr>
</table>

    {% if errorMsg is defined %}
        <div class=\"errorMsg\" align=\"center\">
            {{ errorMsg }}
        </div>
    {% endif %}

    {{ body | raw }}
{% endblock %}
", "setup/index.html.twig", "C:\\Users\\Chris Haas.VENDI\\Development\\base\\templates\\setup\\index.html.twig");
    }
}
