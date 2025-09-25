function formatRupiah(angka) {
    let numberString = angka.toString().replace(/\D/g, "");
    let sisa = numberString.length % 3;
    let rupiah = numberString.substr(0, sisa);
    let ribuan = numberString.substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        let separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    return rupiah;
}