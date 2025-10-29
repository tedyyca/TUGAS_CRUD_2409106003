document.addEventListener('DOMContentLoaded', function() {
    
    const deleteButtons = document.querySelectorAll('.btn-hapus');
    
    const deleteModal = document.getElementById('deleteModal');
    const modalConfirmDelete = document.getElementById('modalConfirmDelete');
    const modalCancelDelete = document.getElementById('modalCancelDelete');
    const modalOverlay = document.querySelector('.modal-overlay');

    if (deleteModal) {
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                
                const deleteUrl = this.getAttribute('href');
                
                modalConfirmDelete.setAttribute('href', deleteUrl);
                
                deleteModal.classList.add('active');
            });
        });

        function closeModal() {
            deleteModal.classList.remove('active');
        }

        if (modalCancelDelete) {
            modalCancelDelete.addEventListener('click', closeModal);
        }

        if (modalOverlay) {
            modalOverlay.addEventListener('click', function(event) {
                if (event.target === modalOverlay) {
                    closeModal();
                }
            });
        }
    }


    
    const flashMessages = document.querySelectorAll('.message');
    
    flashMessages.forEach(function(message) {
        
        setTimeout(function() {
            message.style.opacity = '0';
            setTimeout(() => message.style.display = 'none', 500);
        }, 5000); 

        const closeButton = document.createElement('a');
        closeButton.href = '#';
        closeButton.className = 'message-close';
        closeButton.innerHTML = '&times;'; 
        message.appendChild(closeButton);
        
        closeButton.addEventListener('click', function(event) {
            event.preventDefault();
            message.style.opacity = '0';
            setTimeout(() => message.style.display = 'none', 500);
        });
    });

});