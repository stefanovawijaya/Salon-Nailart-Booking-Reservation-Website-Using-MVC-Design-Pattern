const tabs = document.querySelectorAll(".tab");
const galleryDesain = document.querySelector(".gallery");
const galleryLayanan = document.querySelector(".gallery-layanan");
const voucherDiskon = document.querySelector(".voucher-diskon");

tabs.forEach((tab, index) => {
tab.addEventListener("click", () => {
    tabs.forEach(t => t.classList.remove("active"));
    tab.classList.add("active");

    galleryDesain.style.display = "none";
    galleryLayanan.style.display = "none";
    voucherDiskon.style.display = "none";

    if (index === 0) {
    galleryDesain.style.display = "grid";
    } else if (index === 1) {
    galleryLayanan.style.display = "grid";
    } else if (index === 2) {
    voucherDiskon.style.display = "grid";
    }
});
});

function showImageDetail(src, name, desc, date) {
document.getElementById("imageModal").style.display = "flex";
document.getElementById("modalImage").src = src;
document.getElementById("modalTitle").textContent = name;       
document.getElementById("modalDesc").textContent = desc; 
document.getElementById("modalDate").textContent = date;
}

function showImageTreatmentDetail(src, name, price, date) {
document.getElementById("treatmentModal").style.display = "flex";
document.getElementById("modalImageTreatment").src = src;
document.getElementById("modalTreatmentName").textContent = name;
document.getElementById("modalPrice").textContent = `Rp ${price}`;
document.getElementById("modalTanggal").textContent = date;
}

function showImageVoucherDetail(src, code, value, date) {
document.getElementById("voucherModal").style.display = "flex";
document.getElementById("modalImageVoucher").src = src;
document.getElementById("modalVoucherCode").textContent = code;
document.getElementById("modalValue").textContent = `Rp ${value}`;
document.getElementById("modalTanggall").textContent = date;
}

function closeModal() {
document.getElementById("imageModal").style.display = "none";
}

function closeModalTreatment() {
document.getElementById("treatmentModal").style.display = "none";
}

function closeModalVoucher() {
document.getElementById("voucherModal").style.display = "none";
}