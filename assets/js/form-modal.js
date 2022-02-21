function urlParam(name) {
	const results = new RegExp("[?&]" + name + "=([^&#]*)").exec(
		window.location.href
	);

	return results[1] || 0;
}

// fungsi saat menekan tombol delete
function deleteModal(linkHapus, pesan = "Data") {
	$("#deleteBody span").html(pesan);
	$("#deleteLink").attr("href", linkHapus);
}

// fungsi reset form seperti semula
function resetForm(linkTambah, judulForm) {
	console.log("test reset");
	// // trigger reset
	// $("#formModal form").trigger("reset");
	// // const formAction = $('#formModal form').attr('action');

	// // ubah action dari form menjadi tambah
	// // let tambahAction = window.location.href + "&aksi=tambah";

	// $("#formModal form").attr("action", linkTambah);

	// // ubah judul form
	// $("#formModalLabel").html(judulForm);

	// // ubah tombol edit menjadi tambah
	// $("#formModal form input[type=submit]").val("Tambah");

	// // reset modal hapus
	// const deleteHref = $("#deleteLink").attr("href").substr(0, 26);
	// $("#deleteModal a").attr("href", deleteHref);
}

// kode diatas tidak perlu diubah, ubah, tambah dan sesuaikan lah kode dibawah, ex: tinggal copas dan sesuaikan
