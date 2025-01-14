document.getElementById('closeButton').addEventListener('click', function () {
    window.location.href = '../index.php';
});

// JavaScript function to print a specific div using an iframe
document.getElementById('printButton').addEventListener('click', function () {
    var printContent = document.getElementById('printArea').innerHTML; // Get the content of the specific div
    
    // Create a temporary iframe to load the content
    printReceiptContent(printContent);
});

document.getElementById('printCustomerCopyButton').addEventListener('click', function () {
    var printContent = document.getElementById('printCustomerArea').innerHTML; // Get the content of the specific div
    
    // Create a temporary iframe to load the content
    printReceiptContent(printContent);
});

function printReceiptContent(printContent){
    // Create a temporary iframe to load the content
    var iframe = document.createElement('iframe');
    iframe.style.position = 'absolute';
    iframe.style.width = '0px';
    iframe.style.height = '0px';
    iframe.style.border = 'none';
    document.body.appendChild(iframe);

    // Write the HTML content into the iframe's document
    var doc = iframe.contentWindow.document;
    doc.open();
    doc.write('<html><head><title>Print</title><style>body { font-family: Arial, sans-serif; }</style></head><body>');
    doc.write(printContent);
    doc.write('</body></html>');
    doc.close();

    // Wait for the iframe to load and then trigger the print dialog
    iframe.contentWindow.focus();
    iframe.contentWindow.print();

    // Remove the iframe after printing is done
    document.body.removeChild(iframe);
}