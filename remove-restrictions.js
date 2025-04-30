// Function to remove copy restrictions
function removeCopyRestrictions() {
    // Remove body event handlers
    document.body.oncopy = null;
    document.body.oncut = null;
    document.body.onpaste = null;
    document.body.onkeydown = null;
    document.body.oncontextmenu = null;
    
    // Remove jQuery event handlers
    if (typeof jQuery !== 'undefined') {
        jQuery(document).off('contextmenu');
        jQuery(document).off('keydown');
        jQuery('body').off('contextmenu');
    }
    
    // Remove noselect class and add selectable class
    const noselectElements = document.querySelectorAll('.noselect');
    noselectElements.forEach(element => {
        element.classList.remove('noselect');
        element.style.userSelect = 'text';
        element.style.webkitUserSelect = 'text';
        element.style.mozUserSelect = 'text';
        element.style.msUserSelect = 'text';
    });
    
    // Enable all textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.readOnly = false;
        textarea.disabled = false;
        textarea.style.backgroundColor = 'white';
        textarea.classList.remove('noselect');
    });
    
    // Override keydown restrictions
    document.onkeydown = null;
    document.addEventListener('keydown', function(e) {
        return true;
    }, true);
    
    // Override context menu prevention
    document.oncontextmenu = null;
    document.addEventListener('contextmenu', function(e) {
        return true;
    }, true);
    
    // Add custom copy handler
    document.addEventListener('copy', function(e) {
        e.stopPropagation();
    }, true);
    
    // Add custom paste handler
    document.addEventListener('paste', function(e) {
        e.stopPropagation();
    }, true);
    
    // Add custom cut handler
    document.addEventListener('cut', function(e) {
        e.stopPropagation();
    }, true);
    
    // Make all content selectable
    document.querySelectorAll('*').forEach(element => {
        element.style.userSelect = 'text';
        element.style.webkitUserSelect = 'text';
        element.style.mozUserSelect = 'text';
        element.style.msUserSelect = 'text';
    });

    console.log('Copy restrictions removed successfully!');
}

// Add a button to the page for easy access
function addRemoveRestrictionsButton() {
    // Remove existing button if it exists
    const existingButton = document.getElementById('remove-restrictions-btn');
    if (existingButton) {
        existingButton.remove();
    }

    const button = document.createElement('button');
    button.id = 'remove-restrictions-btn';
    button.textContent = 'Remove Restrictions';
    button.style.position = 'fixed';
    button.style.top = '10px';
    button.style.right = '10px';
    button.style.zIndex = '999999';
    button.style.padding = '10px';
    button.style.backgroundColor = '#4CAF50';
    button.style.color = 'white';
    button.style.border = 'none';
    button.style.borderRadius = '5px';
    button.style.cursor = 'pointer';
    button.style.fontWeight = 'bold';
    button.style.boxShadow = '0 2px 5px rgba(0,0,0,0.2)';
    button.onclick = function() {
        removeCopyRestrictions();
        this.textContent = 'Restrictions Removed!';
        this.style.backgroundColor = '#45a049';
        setTimeout(() => {
            this.textContent = 'Remove Restrictions';
            this.style.backgroundColor = '#4CAF50';
        }, 2000);
    };
    
    document.body.appendChild(button);
}

// Add the button when the page loads
window.addEventListener('load', addRemoveRestrictionsButton);

// Also add the button if the page is already loaded
if (document.readyState === 'complete') {
    addRemoveRestrictionsButton();
}

// Export the function for direct console use
window.removeCopyRestrictions = removeCopyRestrictions; 