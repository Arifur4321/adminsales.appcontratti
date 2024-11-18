<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .header, .footer {
            width: 100%;
            text-align: center;
            position: fixed;
            background-color: #fff; /* Optional: Background color to make headers/footers stand out */
        }
        .header {
            top: 0px;
            height: 60px; /* Fixed height for the header */
        }
        .footer {
            bottom: 0px;
            height: 60px; /* Fixed height for the footer */
        }
        .content {
            padding: 0 20px;
            /* Conditional margins based on presence of header and footer */
            margin-top: {{ $headerContent ? '80px' : '0' }}; /* 60px height + 20px buffer */
            margin-bottom: {{ $footerContent ? '80px' : '0' }}; /* 60px height + 20px buffer */
        }
        .page-break {
            page-break-after: always;
            page-break-inside: avoid;
        }
        @page {
            margin: 0;
        }
    </style>
</head>
<body>
    @if ($headerContent)
        <div class="header">
            {!! $headerContent !!}
        </div>
    @endif

    <div class="content">
        {!! $content !!}
    </div>

    @if ($footerContent)
        <div class="footer">
            {!! $footerContent !!}
        </div>
    @endif

    <script type="text/php">
        if (isset($pdf)) {
            $pdf->page_script('
                // Handling headers and footers on multiple pages
                if ($PAGE_COUNT > 1) {
                    if ($PAGE_NUMBER == 1) {
                        if (isset($headerContent) && !$headerEveryPage) {
                            // Adding header on the first page only
                            $pdf->text(20, 20, "{!! $headerContent !!}", ["size" => 10]);
                        }
                        if (isset($footerContent) && !$footerEveryPage) {
                            // Adding footer on the first page only
                            $pdf->text(20, $pdf->get_height() - 40, "{!! $footerContent !!}", ["size" => 10]);
                        }
                    }
                    if ($PAGE_NUMBER > 1) {
                        if ($headerEveryPage && isset($headerContent)) {
                            // Adding header on every page
                            $pdf->text(20, 20, "{!! $headerContent !!}", ["size" => 10]);
                        }
                        if ($footerEveryPage && isset($footerContent)) {
                            // Adding footer on every page
                            $pdf->text(20, $pdf->get_height() - 40, "{!! $footerContent !!}", ["size" => 10]);
                        }
                    }
                } else {
                    if ($headerEveryPage && isset($headerContent)) {
                        // Single page document, adding header
                        $pdf->text(20, 20, "{!! $headerContent !!}", ["size" => 10]);
                    }
                    if ($footerEveryPage && isset($footerContent)) {
                        // Single page document, adding footer
                        $pdf->text(20, $pdf->get_height() - 40, "{!! $footerContent !!}", ["size" => 10]);
                    }
                }
            ');
        }
    </script>
</body>
</html>
