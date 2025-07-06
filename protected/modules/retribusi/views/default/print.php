<button onclick="cobaprint()">Print</button>
<script src="https://cdn.jsdelivr.net/npm/recta/dist/recta.js"></script>
<script type="text/javascript">
    function cobaprint() {
        var printer = new Recta('123456789012', '1811')
        // console.log("kesini");
        printer.open().then(() => {
            printer.align('center')
                .text('Hello World !!')
                .bold(true)
                .text('This is bold text')
                .bold(false)
                .underline(true)
                .text('This is underline text')
                .underline(false)
                .barcode('UPC-A', '123456789012')
                .cut()
                .print()
        })
    }
    // .barcode('UPC-A', '6747608510')
</script>