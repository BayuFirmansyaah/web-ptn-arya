function uploadFile(pesertaId, type) {
  const fd = new FormData();
  fd.append("file", document.getElementById("fileInput").files[0]);
  fd.append("peserta_id", pesertaId);
  fd.append("type", type);

  fetch("api/upload.php", { method: "POST", body: fd })
    .then((r) => r.json())
    .then((j) => {
      if (j.ok) {
        alert("Upload sukses");
        location.reload();
      } else {
        alert(j.error || "Gagal");
      }
    });
}
