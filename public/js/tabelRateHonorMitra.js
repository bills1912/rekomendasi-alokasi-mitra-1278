let pengalokasianTabel = $('#rate-honor-mitra').DataTable({
    paging: true,
    columnDefs: [
        // { targets: [3, 4], visible: false },
        { className: "dt-head-center", targets: '_all' },
    ],
});

$('#daftar-honor-mitra').DataTable({
    columnDefs: [
        { className: "dt-head-center", targets: '_all' },
        { className: "dt-body-center", targets: [3, 4, 5] }
    ]
});

$('#btn-edit-honor').click(function (e) {
    Swal.fire({
        icon: "error",
        title: "Oops...",
        html: "Tidak bisa lagi mengedit honor mitra!<br><strong>Silahkan hubungi admin aplikasi.<\strong>",
    });
});

console.log(parseInt(Cookies.get('jumlah-mitra-terpilih')))

$('#btn-alokasikan-honor').click(function (e) {
    e.preventDefault();
    
    let form = $('#form-alokasi-honor-mitra');
    if (pengalokasianTabel.page.info().recordsTotal < parseInt(Cookies.get('jumlah-mitra-terpilih'))) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            html: "Jumlah mitra yang dialokasikan belum terpenuhi!<br><strong>Silahkan alokasikan mitra lagi.<\strong>",
        });
    } else {
        Swal.fire({
            title: "Alokasikan Honor Mitra",
            text: "Apakah kamu yakin ingin mengalokasikan honor mitra tersebut?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, alokasikan",
            cancelButtonText: "Tidak",
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }
});

document.querySelectorAll('input[type-currency="IDR"]').forEach((element) => {
    element.addEventListener('keyup', function (e) {
        let cursorPosition = this.selectionStart;
        let value = parseInt(this.value.replace(/[^,\d]/g, ''));
        let originalLength = this.value.length;
        if (isNaN(value)) {
            this.value = "";
        } else {
            this.value = value.toLocaleString('id-ID', {
                currency: 'IDR',
                style: 'currency',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
            cursorPosition = this.value.length - originalLength + cursorPosition;
            this.setSelectionRange(cursorPosition, cursorPosition);
        };
    });
});