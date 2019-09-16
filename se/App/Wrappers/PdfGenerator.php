<?php

namespace Platform\App\Wrappers;

use Illuminate\Http\Response;
use Knp\Snappy\Pdf;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfGenerator
{

    /**
     * @var \Knp\Snappy\Pdf
     */
    protected $pdf;

    /**
     * @var array
     */
    protected $options = [];


    /**
     * @param \Knp\Snappy\Pdf $snappy
     */
    public function __construct()
    {
        $this->pdf = new Pdf(env('WKHTMLTOPDF_BIN_PATH'));
    }

    /**
     * Get the Snappy instance.
     *
     * @return \Knp\Snappy\Pdf
     */
    public function pdf()
    {
        return $this->pdf;
    }

    /**
     * Set the paper size (default A4)
     *
     * @param string $paper
     * @param string $orientation
     * @return $this
     */
    public function setPaper($paper, $orientation = null)
    {
        $this->pdf->setOption('page-size', $paper);
        if ($orientation) {
            $this->pdf->setOption('orientation', $orientation);
        }
        return $this;
    }

    /**
     * Set the orientation (default portrait)
     *
     * @param string $orientation
     * @return static
     */
    public function setOrientation($orientation)
    {
        $this->pdf->setOption('orientation', $orientation);
        return $this;
    }

    /**
     * Set different wkhtmltopdf option
     *
     * @param [type] $name  [description]
     * @param [type] $value [description]
     */
    public function setOption($name, $value)
    {
        $this->pdf->setOption($name, $value);
        return $this;
    }

    public function setOptions($options)
    {
        $this->pdf->setOptions($options);
        return $this;
    }

    /**
     * Set the header for pdf from html file
     * @param string $header Path to html header file
     */
    public function setHeader($header)
    {
        $this->setOption('header-html', $header);
        return $this;
    }

    /**
     * Set the footer for pdf from html file
     * @param string $footer Path to html footer file
     */
    public function setFooter($footer)
    {
        $this->setOption('footer-html', $footer);
        return $this;
    }

    /**
     * Set the header from view
     * @param string $view
     * @param array  $data
     */
    public function setHeaderView($view, array $data = [])
    {
        $this->setHeader(view($view, $data)->render());
        return $this;
    }

    /**
     * Set the footer from view
     * @param string $view
     * @param array  $data
     */
    public function setFooterView($view, array $data = [])
    {
        $this->setFooter(view($view, $data)->render());
        return $this;
    }

    /**
     * Load a HTML string
     *
     * @param string $string
     * @return static
     */
    public function loadHTML($string)
    {
        $this->html = (string) $string;
        $this->file = null;
        return $this;
    }

    /**
     * Load a HTML file
     *
     * @param string $file
     * @return static
     */
    public function loadFile($file)
    {
        $this->html = null;
        $this->file = $file;
        return $this;
    }

    /**
     * Load a View and convert to HTML
     *
     * @param string $view
     * @param array $data
     * @param array $mergeData
     * @return static
     */
    public function loadView($view, array $data = [], array $mergeData = [])
    {
        $this->html = view($view, $data, $mergeData)->render();
        $this->file = null;
        return $this;
    }

    /**
     * Output the PDF as a string.
     *
     * @return string The rendered PDF as string
     * @throws \InvalidArgumentException
     */
    public function output()
    {
        if ($this->html) {
            return $this->pdf->getOutputFromHtml($this->html, $this->options);
        }

        if ($this->file) {
            return $this->pdf->getOutput($this->file, $this->options);
        }

        throw new \InvalidArgumentException('PDF Generator requires a html or file in order to produce output.');
    }

    /**
     * Save the PDF to a file
     *
     * @param $filename
     * @return static
     */
    public function save($filename)
    {
        if ($this->html) {
            $this->pdf->generateFromHtml($this->html, $filename, $this->options);
        } elseif ($this->file) {
            $this->pdf->generate($this->file, $filename, $this->options);
        }

        return $this;
    }

    /**
     * Make the PDF downloadable by the user
     *
     * @param string $filename
     * @return Response
     */
    public function download($filename = 'document.pdf')
    {
        return new Response($this->output(), 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' =>  'attachment; filename="'.$filename.'"'
        ));
    }

    /**
     * Return a response with the PDF to show in the browser
     *
     * @param string $filename
     * @return StreamedResponse
     */
    public function stream($filename = 'document.pdf')
    {
        return new StreamedResponse(function () {
            echo $this->output();
        }, 200, array(
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'"',
        ));
    }

    /**
     * Call Snappy instance.
     *
     * Also shortcut's
     * ->html => loadHtml
     * ->view => loadView
     * ->file => loadFile
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, array $arguments)
    {
        $method = 'load' . ucfirst($name);
        if (method_exists($this, $method)) {
            return call_user_func_array(array($this, $method), $arguments);
        }

        return call_user_func_array(array($this->snappy, $name), $arguments);
    }
}
