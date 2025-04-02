const canvas = document.createElement('canvas');
canvas.width = 48;
canvas.height = 48;
const ctx = canvas.getContext('2d');

// Draw a simple lock icon
ctx.fillStyle = '#4CAF50';
ctx.beginPath();
// Lock body
ctx.roundRect(12, 20, 24, 20, 3);
ctx.fill();
// Lock shackle
ctx.beginPath();
ctx.lineWidth = 4;
ctx.strokeStyle = '#4CAF50';
ctx.arc(24, 20, 8, Math.PI, 2 * Math.PI);
ctx.stroke();

// Convert to PNG data URL
const dataUrl = canvas.toDataURL('image/png');
console.log(dataUrl); 