// jQuery(document).ready(function($) {
//     $('.editable-field').on('blur', function() {
//         const $field = $(this);
//         const tourId = $field.data('tour-id');
//         const fieldName = $field.data('field-name');
//         const value = $field.text();
        
//         $.ajax({
//             url: 'includes/ajax-handler.php',
//             type: 'POST',
//             dataType: 'json',
//             data: {
//                 action: 'quick_edit_tour',
//                 tour_id: tourId,
//                 field: fieldName,
//                 value: value
//             },
//             success: function(response) {
//                 if (!response.success) {
//                     alert('Error saving changes');
//                 }
//             }
//         });
//     });
    
//     // Delete tour with confirmation
//     $('.delete-tour').on('click', function(e) {
//         e.preventDefault();
//         const $link = $(this);
//         const tourId = $link.data('tour-id');
        
//         if (confirm('Are you sure you want to delete this tour?')) {
//             $.ajax({
//                 url: 'includes/ajax-handler.php',
//                 type: 'POST',
//                 dataType: 'json',
//                 data: {
//                     action: 'delete_tour',
//                     tour_id: tourId
//                 },
//                 success: function(response) {
//                     if (response.success) {
//                         $link.closest('tr').fadeOut();
//                     } else {
//                         alert('Error deleting tour');
//                     }
//                 }
//             });
//         }
//     });
// });
document.addEventListener('DOMContentLoaded', function() {

    // Toggle sidebar collapse/expand
    const menuToggle = document.getElementById('menuToggle');
    const body = document.body;
    
    menuToggle.addEventListener('click', function() {
        body.classList.toggle('sidebar-collapsed');
        
        // Save preference in localStorage
        if (body.classList.contains('sidebar-collapsed')) {
            localStorage.setItem('sidebarCollapsed', 'true');
        } else {
            localStorage.setItem('sidebarCollapsed', 'false');
        }
    });
    
    // Check for saved preference
    if (localStorage.getItem('sidebarCollapsed') === 'true') {
        body.classList.add('sidebar-collapsed');
    }
    
    // Profile dropdown toggle
    const userAvatar = document.getElementById('userAvatar');
    const profileDropdown = document.getElementById('profileDropdown');
    
    userAvatar.addEventListener('click', function(e) {
        e.stopPropagation();
        profileDropdown.classList.toggle('show');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function() {
        profileDropdown.classList.remove('show');
    });
    
// Initialize charts
//document.addEventListener('DOMContentLoaded', function() {
    // Your chart initialization code here
});