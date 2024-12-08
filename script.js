document.getElementById('cpu-btn').addEventListener('click', function() {
    fetchParts('CPU');
});

document.getElementById('cpu-cooler-btn').addEventListener('click', function() {
    fetchParts('CPU Cooler');
});

document.getElementById('gpu-btn').addEventListener('click', function() {
    fetchParts('GPU');
});

document.getElementById('ram-btn').addEventListener('click', function() {
    fetchParts('RAM');
});

document.getElementById('storage-btn').addEventListener('click', function() {
    fetchParts('Storage');
});

document.getElementById('psu-btn').addEventListener('click', function() {
    fetchParts('PSU');
});

document.getElementById('case-btn').addEventListener('click', function() {
    fetchParts('Case');
});

document.getElementById('motherboard-btn').addEventListener('click', function() {
    fetchParts('Motherboard');
});

function fetchParts(category) {
    const partsContainer = document.getElementById('parts-container');
    partsContainer.innerHTML = '<p>Loading parts...</p>';

    fetch(`fetch_parts.php?category=${category}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                let html = `<h2>Parts for ${category}</h2><div class="table-container">`;
                
                data.forEach(part => {
                    html += `<div class="part-sheet">
                                <h3>${part.name}</h3>
                                <table>
                                    <tr><th>Name</th><td>${part.name}</td></tr>`;
                    
                    // Display fields based on category
                    if (category === 'CPU') {
                        html += `
                            <tr><th>Cores</th><td>${part.core}</td></tr>
                            <tr><th>Core Clock</th><td>${part.core_clock}</td></tr>
                            <tr><th>Boost Clock</th><td>${part.boost_clock}</td></tr>
                            <tr><th>Microarchitecture</th><td>${part.microarchitecture}</td></tr>
                            <tr><th>TDP</th><td>${part.tdp}</td></tr>
                            <tr><th>iGPU</th><td>${part.igpu}</td></tr>
                            <tr><th>Rating</th><td>${part.rating}</td></tr>
                            <tr><th>Price</th><td>$${part.price}</td></tr>
                            <tr><th>Link</th><td><a href="${part.link}" target="_blank">View More</a></td></tr>
                        `;
                    } else if (category === 'RAM') {
                        html += `
                            <tr><th>Generation</th><td>${part.generation}</td></tr>
                            <tr><th>Speed</th><td>${part.speed}</td></tr>
                            <tr><th>Price</th><td>$${part.price}</td></tr>
                            <tr><th>Link</th><td><a href="${part.link}" target="_blank">View More</a></td></tr>
                        `;
                    } else if (category === 'GPU') {
                        html += `
                            <tr><th>VRAM</th><td>${part.vram}</td></tr>
                            <tr><th>Recommended PSU</th><td>${part.recommended_psu}</td></tr>
                            <tr><th>Price</th><td>$${part.price}</td></tr>
                            <tr><th>Link</th><td><a href="${part.link}" target="_blank">View More</a></td></tr>
                        `;
                    } else if (category === 'Storage') {
                        html += `
                            <tr><th>Read Speed</th><td>${part.read_speed}</td></tr>
                            <tr><th>Write Speed</th><td>${part.write_speed}</td></tr>
                            <tr><th>Form Factor</th><td>${part.form_factor}</td></tr>
                            <tr><th>Price</th><td>$${part.price}</td></tr>
                            <tr><th>Link</th><td><a href="${part.link}" target="_blank">View More</a></td></tr>
                        `;
                    } else if (category === 'PSU') {
                        html += `
                            <tr><th>Wattage</th><td>${part.wattage}</td></tr>
                            <tr><th>Certification</th><td>${part.certification}</td></tr>
                            <tr><th>Price</th><td>$${part.price}</td></tr>
                            <tr><th>Link</th><td><a href="${part.link}" target="_blank">View More</a></td></tr>
                        `;
                    } else if (category === 'Case') {
                        html += `
                            <tr><th>Type</th><td>${part.type}</td></tr>
                            <tr><th>Dimensions</th><td>${part.dimensions}</td></tr>
                            <tr><th>Price</th><td>$${part.price}</td></tr>
                            <tr><th>Link</th><td><a href="${part.link}" target="_blank">View More</a></td></tr>
                        `;
                    } else if (category === 'Motherboard') {
                        html += `
                            <tr><th>Socket</th><td>${part.socket}</td></tr>
                            <tr><th>Memory Max</th><td>${part.memory_max}</td></tr>
                            <tr><th>Memory Slots</th><td>${part.memory_slots}</td></tr>
                            <tr><th>Price</th><td>$${part.price}</td></tr>
                            <tr><th>Link</th><td><a href="${part.link}" target="_blank">View More</a></td></tr>
                        `;
                    } else if (category === 'CPU Cooler') {
                        html += `
                            <tr><th>RPM</th><td>${part.rpm}</td></tr>
                            <tr><th>Noise</th><td>${part.noise}</td></tr>
                            <tr><th>Price</th><td>$${part.price}</td></tr>
                            <tr><th>Link</th><td><a href="${part.link}" target="_blank">View More</a></td></tr>
                        `;
                    }

                    html += `</table></div>`;
                });
                html += '</div>';
                partsContainer.innerHTML = html;
            } else {
                partsContainer.innerHTML = `<p>No parts available in this category.</p>`;
            }
        })
        .catch(error => {
            partsContainer.innerHTML = `<p>Error loading parts: ${error}</p>`;
        });
}
