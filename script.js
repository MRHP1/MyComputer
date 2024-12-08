document.addEventListener('DOMContentLoaded', () => {
    fetch('fetch_parts.php')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('parts-container');
            data.forEach(part => {
                const partDiv = document.createElement('div');
                partDiv.className = 'part';
                partDiv.innerHTML = `
                    <h2>${part.name}</h2>
                    <p>Category: ${part.category}</p>
                    <p>Price: $${part.price}</p>
                    <p>Stock: ${part.stock}</p>
                    <p>${part.description}</p>
                `;
                container.appendChild(partDiv);
            });
        })
        .catch(error => console.error('Error fetching parts:', error));
});
