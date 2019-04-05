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
class __TwigTemplate_a15281f06c1a3eb2f6f1df8d7b93a553ab53db5862ce18398aeb72f82a9d8a29 extends \Twig\Template
{
    private $source;

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
            'body' => [$this, 'block_body'],
            'admin_error' => [$this, 'block_admin_error'],
            'admin_body' => [$this, 'block_admin_body'],
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        // line 1
        $this->displayBlock('body', $context, $blocks);
    }

    public function block_body($context, array $blocks = [])
    {
        // line 2
        echo "<TABLE WIDTH=\"100%\" BORDER=0 CELLSPACING=0 CELLPADDING=5>
    <TR>
      <TD class=\"mainheader\"> &nbsp </TD>
      <TD class=\"mainheadertitle\">
         Basic Analysis and Security Engine (BASE) Setup Program
      </TD>
    </TR>
</TABLE>
    ";
        // line 10
        $this->displayBlock('admin_error', $context, $blocks);
        // line 12
        echo "    ";
        $this->displayBlock('admin_body', $context, $blocks);
    }

    // line 10
    public function block_admin_error($context, array $blocks = [])
    {
        // line 11
        echo "    ";
    }

    // line 12
    public function block_admin_body($context, array $blocks = [])
    {
        // line 13
        echo "    ";
    }

    public function getTemplateName()
    {
        return "setup/index.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  71 => 13,  68 => 12,  64 => 11,  61 => 10,  56 => 12,  54 => 10,  44 => 2,  38 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "setup/index.html.twig", "C:\\Users\\Chris Haas.VENDI\\Development\\base\\templates\\setup\\index.html.twig");
    }
}
