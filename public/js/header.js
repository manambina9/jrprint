document.querySelector('.btn-connect').addEventListener('mouseover', function() {
    this.style.transform = 'scale(1.05)';
    this.style.boxShadow = '0 8px 15px rgba(0, 0, 0, 0.2)';
  });
  
  document.querySelector('.btn-connect').addEventListener('mouseout', function() {
    this.style.transform = 'scale(1)';
    this.style.boxShadow = 'none';
  });
  