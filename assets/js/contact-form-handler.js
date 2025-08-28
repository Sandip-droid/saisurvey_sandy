document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('contact-form');
  const loading = form.querySelector('.loading');
  
  form.addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent default form submission (page reload)

    // Show loading spinner
    loading.style.display = 'block';

    // Get form data
    const formData = new FormData(form);

    fetch('forms/quote.php', {   // 👈 contact.php की जगह तुम्हारा quote.php
      method: 'POST',
      body: formData,
    })
    .then(response => response.json())  // तुम्हारा PHP json_encode कर रहा है
    .then(data => {
      loading.style.display = 'none';

      if (data.status === "success") {
        Swal.fire({
          icon: "success",
          title: "Message Sent!",
          text: "We will get back to you shortly."
        });
        form.reset();
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: data.message || "Something went wrong!"
        });
      }
    })
    .catch(error => {
      loading.style.display = 'none';
      Swal.fire({
        icon: "error",
        title: "Server Error",
        text: "Please try again later!"
      });
      console.error('Error:', error);
    });
  });
});
