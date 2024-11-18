<?php

namespace App\Http\Controllers;


use App\Models\Product;
use Illuminate\Http\Request;
 
use PDF;
use Dompdf\Dompdf;
 
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

 

class ProductController extends Controller

{
    
        public function index()
        {
            // Get the authenticated user
            $user = Auth::user();
    
            // Fetch products where company_id matches the authenticated user's company_id
            $products = Product::where('company_id', $user->company_id)->get();
    
            return view('ProductList', compact('products'));
        }
    
  
//********************************* */
 
    public function generatePdfforSales(Request $request)
    {
        $editorData = $request->input('editorData');
        $headerCheckbox = filter_var($request->input('headerCheckbox'), FILTER_VALIDATE_BOOLEAN);
        $footerCheckbox = filter_var($request->input('footerCheckbox'), FILTER_VALIDATE_BOOLEAN);
        $headerValue = $request->input('headerValue');
        $footerValue = $request->input('footerValue');
        $headerLocation = $request->input('headerLocation');
        $footerLocation = $request->input('footerLocation');

        $htmlContent = $editorData;
        $htmlContent = $this->processImageTags($htmlContent);

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->set_option('isHtml5ParserEnabled', true);

        $htmlContent = str_replace('src="http://localhost:8000/media/', 'src="' . public_path('media') . '/', $htmlContent);

        date_default_timezone_set('Europe/Rome');
        $creationDate = date('Y-m-d H:i:s');

        $fullHtmlContent = '<html><head><style>
            body { 
                margin: 0;
                padding-top: 70px; /* Increased padding to avoid overlap with header */
                padding-bottom: 70px; /* Increased padding to avoid overlap with footer */
            }
            .header, .footer {
                position: fixed;
                width: 100%;
                font-size: 10px;
                height: 50px;
                background-color: white; /* Ensures it covers any underlying text */
                z-index: 1000; /* Keeps it above other content */
            }
            .header {
                top: 0;
                text-align: right;
            }
            .footer {
                bottom: 0;
                text-align: right;
            }
            .creation-date {
                position: fixed;
                bottom: 0;
                left: 0;
                font-size: 10px;
            }
            .page-number {
                position: fixed;
                bottom: 0;
                right: 0;
                font-size: 10px;
            }
            .page-number:before {
                content: "Page " counter(page);
            }
            table {
                margin-left: auto;
                margin-right: auto;
                border-collapse: collapse;
                width: 90%;
            }
            table, th, td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }
            img {
                margin: 10px;
            }
        </style></head><body>';

        // Add creation date and page number for every page
        $fullHtmlContent .= '<div class="creation-date">' . $creationDate . '</div>';
        $fullHtmlContent .= '<div class="page-number"></div>';

        // Add header and footer based on user selection
        if ($headerCheckbox && $headerLocation === 'every') {
            $fullHtmlContent .= '<div class="header">' . $headerValue . '</div>';
        }

        if ($footerCheckbox && $footerLocation === 'every') {
            $fullHtmlContent .= '<div class="footer">' . $footerValue . '</div>';
        }

        // Add content
        $fullHtmlContent .= $htmlContent;

        // Add header for the first page if selected
        if ($headerCheckbox && $headerLocation === 'first') {
            $firstPageHeader = '<div class="header">' . $headerValue . '</div>';
            $fullHtmlContent = str_replace('<body>', '<body>' . $firstPageHeader, $fullHtmlContent);
        }

        // Add footer for the first page if selected
        if ($footerCheckbox && $footerLocation === 'first') {
            $firstPageFooter = '<div class="footer">' . $footerValue . '</div>';
            $fullHtmlContent = str_replace('</body>', $firstPageFooter . '</body>', $fullHtmlContent);
        }

        $fullHtmlContent .= '</body></html>';

        $dompdf->loadHtml($fullHtmlContent);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfContent = $dompdf->output();
        $filename = 'contract_' . time() . '.pdf';

        Storage::disk('public')->put('pdf/' . $filename, $pdfContent);
        $pdfUrl = Storage::url('pdf/' . $filename);

        session(['html_content' => $htmlContent]);

        return response()->json(['pdf_url' => $pdfUrl]);
    }

    private function processImageTags($htmlContent)
    {
        // Define regex patterns for different image tags
        $patterns = [
            'right' => '/<figure class="image image-style-side"><img[^>]*><\/figure>/i',
            'left' => '/<p><img[^>]*><\/p>/i',
            'middle' => '/<figure class="image"><img[^>]*><\/figure>/i'
        ];

        foreach ($patterns as $position => $pattern) {
            preg_match_all($pattern, $htmlContent, $matches);

            foreach ($matches[0] as $imgTag) {
                // Extract src, width, and height attributes
                preg_match('/src="([^"]*)"/i', $imgTag, $srcMatch);
                preg_match('/width="([^"]*)"/i', $imgTag, $widthMatch);
                preg_match('/height="([^"]*)"/i', $imgTag, $heightMatch);

                $src = $srcMatch[1] ?? '';
                $width = $widthMatch[1] ?? '';
                $height = $heightMatch[1] ?? '';

                // Generate the new image tag based on the position
                switch ($position) {
                    case 'right':
                        $newImgTag = "<img src=\"{$src}\" width=\"{$width}\" height=\"{$height}\" style=\"float: right; margin: 10px;\">";
                        break;
                    case 'left':
                        $newImgTag = "<img src=\"{$src}\" width=\"{$width}\" height=\"{$height}\" style=\"float: left; margin: 10px;\">";
                        break;
                    case 'middle':
                    default:
                        $newImgTag = "<div style=\"text-align: center;\"><img src=\"{$src}\" width=\"{$width}\" height=\"{$height}\" style=\"display: inline-block;\"></div>";
                        break;
                }

                // Replace the old image tag with the new one in the HTML content
                $htmlContent = str_replace($imgTag, $newImgTag, $htmlContent);
            }
        }

        return $htmlContent;
    }


    // public function generatePdfforSales(Request $request)
    // {
    //     $editorData = $request->input('editorData');

    //     $htmlContent = $editorData;

    //     // Process image tags
    //     $htmlContent = $this->processImageTags($htmlContent);

    //     // Set options to allow for remote file access
    //     $options = new Options();
    //     $options->set('isRemoteEnabled', true);

    //     $dompdf = new Dompdf($options);
    //     $dompdf->set_option('isHtml5ParserEnabled', true);

    //     // Update image paths to be absolute URLs
    //     $htmlContent = str_replace('src="http://localhost:8000/media/', 'src="' . public_path('media') . '/', $htmlContent);

    //     // Add CSS for date and page numbers
    //     // Set the timezone to Italian timezone
    //     date_default_timezone_set('Europe/Rome');

    //     // Now generate the creation date with the correct timezone
    //     $creationDate = date('Y-m-d H:i:s');

    //     $fullHtmlContent = '
    //     <html>
    //     <head>
    //         <style>
    //             body { 
    //                 margin: 0;
    //                 padding-top: 20px; /* Reduced padding for less gap between header and content */
    //                 padding-bottom: 50px; /* Adjust as needed for footer space */
    //             }
    //             .footer {
    //                 position: fixed;
    //                 bottom: 0;
    //                 width: 100%;
    //                 font-size: 10px;
    //                 height: 50px; /* Adjust as needed */
    //             }
    //             .footer-left {
    //                 float: left;
    //                 text-align: left;
    //             }
    //             .footer-right {
    //                 float: right;
    //                 text-align: right;
    //             }
    //             .page-number:before {
    //                 content: "Page " counter(page); /* Updated content for page numbering */
    //             }
    //             table {
    //                 margin-left: auto;
    //                 margin-right: auto;
    //                 border-collapse: collapse;
    //                 width: 90%; /* Adjust width as necessary */
    //             }
    //             table, th, td {
    //                 border: 1px solid black;
    //                 padding: 8px;
    //                 text-align: left;
    //             }
    //         </style>
    //     </head>
    //     <body>
    //         <div class="footer">
    //             <div class="footer-left">' . $creationDate . '</div>
    //             <div class="footer-right page-number"></div>
    //         </div>';

    //     $fullHtmlContent .= $htmlContent;
    //     $fullHtmlContent .= '</body></html>';

    //     $dompdf->loadHtml($fullHtmlContent);
    //     $dompdf->setPaper('A4', 'portrait');
    //     $dompdf->render();

    //     $pdfContent = $dompdf->output();
    //     $filename = 'contract_' . time() . '.pdf';

    //     // Save the PDF file
    //     Storage::disk('public')->put('pdf/' . $filename, $pdfContent);
    //     $pdfUrl = Storage::url('pdf/' . $filename);

    //     // Store the HTML content in session for later use
    //     session(['html_content' => $htmlContent]);

    //     return response()->json(['pdf_url' => $pdfUrl]);
    // }


// Working fine only for firma qui photo position
// private function processImageTags($htmlContent)
// {
//     // Define regex patterns for different image tags
//     $patterns = [
//         'right' => '/<figure class="image image-style-side"><img[^>]*><\/figure>/i',
//         'left' => '/<p><img[^>]*><\/p>/i',
//         'middle' => '/<figure class="image"><img[^>]*><\/figure>/i'
//     ];

//     foreach ($patterns as $position => $pattern) {
//         preg_match_all($pattern, $htmlContent, $matches);

//         foreach ($matches[0] as $imgTag) {
//             // Extract src, width, and height attributes
//             preg_match('/src="([^"]*)"/i', $imgTag, $srcMatch);
//             preg_match('/width="([^"]*)"/i', $imgTag, $widthMatch);
//             preg_match('/height="([^"]*)"/i', $imgTag, $heightMatch);

//             $src = $srcMatch[1] ?? '';
//             $width = $widthMatch[1] ?? '';
//             $height = $heightMatch[1] ?? '';

//             // Generate the new image tag based on the position
//             switch ($position) {
//                 case 'right':
//                     $newImgTag = "<div style=\"text-align: right;\"><img style=\"aspect-ratio:{$width}/{$height};\" src=\"{$src}\" width=\"{$width}\" height=\"{$height}\"></div>";
//                     break;
//                 case 'left':
//                     $newImgTag = "<div style=\"text-align: left;\"><img src=\"{$src}\" width=\"{$width}\" height=\"{$height}\"></div>";
//                     break;
//                 case 'middle':
//                 default:
//                     $newImgTag = "<div style=\"text-align: center;\"><img style=\"aspect-ratio:{$width}/{$height};\" src=\"{$src}\" width=\"{$width}\" height=\"{$height}\"></div>";
//                     break;
//             }

//             // Replace the old image tag with the new one in the HTML content
//             $htmlContent = str_replace($imgTag, $newImgTag, $htmlContent);
//         }
//     }

//     return $htmlContent;
// }

 public function deletePdf(Request $request)
 {
     // Get the URL of the PDF file to be deleted from the request
     $pdfUrl = $request->input('pdfUrl');

     // Extract the filename from the URL
     $filename = basename($pdfUrl);

     // Delete the PDF file from the storage folder
     Storage::disk('public')->delete('pdf/' . $filename);

     // Return a success response
     return response()->json(['message' => 'PDF deleted successfully']);
 }


 



//****************************************** */

    public function deleteproduct($id)
    {
        $Product = Product::findOrFail($id); // Find the variable by ID
        $Product->delete(); // Delete the variable
        return redirect()->back()->with('success', 'Variable deleted successfully'); // Redirect back with success message
    }

    
        public function saveProduct(Request $request)
        {
            // Validate the request data
            $validatedData = $request->validate([
                'productName' => 'required',
                'description' => 'required',
            ]);

            // Get the authenticated user
            $user = Auth::user();

            // Create a new product instance
            $product = new Product;
            $product->product_name = $validatedData['productName'];
            $product->description = $validatedData['description'];
            $product->company_id = $user->company_id; // Set the company_id from the authenticated user

            // Save the product to the database
            $product->save();

            return response()->json(['message' => 'Product saved successfully']);
        }
    
    // public function saveProduct(Request $request)
    // {
    //     $validatedData = $request->validate([
        
    //         'productName' => 'required',
    //         'description' => 'required',
    
        
    //     ]);

    // $product = new Product;
    // $product->product_name = $validatedData['productName'];
    // $product->description = $validatedData['description'];
    // $product->save();
 
    // return response()->json(['message' => 'Product saved successfully']);
    // }


 
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'product_name' => 'required|string',
            'description' => 'required|string',
            
        ]);
    
        // Find the variable by ID
        $Product = Product::findOrFail($id);
    
        // Update variable details
        $Product->product_name = $request->input('product_name');
        $Product->description = $request->input('description');
    
        // Check if Description field is provided
        if ($request->has('description')) {
            $Product->description = $request->input('description');
        }
    
        // Save the updated variable
        $Product->save();
    
        // Return a response indicating success
        return response()->json(['success' => true, 'message' => 'Variable updated successfully']);
 
    }

}



