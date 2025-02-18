document.addEventListener('DOMContentLoaded', function() {
    const packageContainer = document.querySelector('.package-features');
    const packageInputName = packageContainer.dataset.packageInput;

    packageContainer.querySelectorAll('.feature-card').forEach(card => {

        card.addEventListener('click', function() {
            document.querySelector(`input[name="${packageInputName}"]`).value = this.dataset.packageValue;
            packageContainer.querySelectorAll('.feature-card').forEach(c => {
                c.classList.remove('selected');
            });

            this.classList.add('selected');
        });
    });

    const steps = document.querySelectorAll('.form-step');
    const progressItems = document.querySelectorAll('.step-item');
    const nextBtn = document.querySelector('.next-step');
    const prevBtn = document.querySelector('.prev-step');
    const submitBtn = document.querySelector('[type="submit"]');
    const $form = $('#company-form');
    let currentStep = 0;

    function updateProgress() {
        progressItems.forEach((item, index) => {
            item.classList.toggle('active', index === currentStep);
            item.classList.toggle('completed', index < currentStep);
        });
    }

    function showStep(stepIndex) {
        steps.forEach((step, index) => {
            step.classList.toggle('active', index === stepIndex);
        });

        // Button visibility
        prevBtn.style.display = stepIndex === 0 ? 'none' : 'block';
        nextBtn.style.display = stepIndex === steps.length - 1 ? 'none' : 'block';
        submitBtn.style.display = stepIndex === steps.length - 1 ? 'block' : 'none';

        if (stepIndex === 0) {
            progressItems.forEach(item => item.style.display = 'none');
        } else {
            progressItems.forEach(item => item.style.display = 'inline-block');
        }

        updateProgress();
    }

    nextBtn.addEventListener('click', function() {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    });

    prevBtn.addEventListener('click', function() {
        if (currentStep > 0) {
            currentStep--;
            showStep(currentStep);
        }
    });

    showStep(0);

    const loadingPopup = document.getElementById('loading-popup');

    $form.on('beforeSubmit', function(e) {
        loadingPopup.style.display = 'flex';

        return true;
    });

    let map;
    let marker;

    function initMap() {
        map = L.map('map').setView([51.505, -0.09], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        marker = L.marker([51.505, -0.09], {draggable: true}).addTo(map);

        // Add click listener for map
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateAddressFields(e.latlng);
        });

        // Add marker drag listener
        marker.on('dragend', function(e) {
            updateAddressFields(marker.getLatLng());
        });
    }

    // Current location button
    document.getElementById('current-location').addEventListener('click', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const latlng = L.latLng(position.coords.latitude, position.coords.longitude);
                map.setView(latlng, 15);
                marker.setLatLng(latlng);
                updateAddressFields(latlng);
            }, function(error) {
                alert('Error getting location: ' + error.message);
            }, {
                enableHighAccuracy: true,
                maximumAge: 0,
                timeout: 10000
            });
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    });


    // Address search
    const searchInput = document.getElementById('address-search');
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchAddress(this.value);
        }
    });

    function searchAddress(query) {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    const latlng = L.latLng(data[0].lat, data[0].lon);
                    map.setView(latlng, 15);
                    marker.setLatLng(latlng);
                    updateAddressFields(latlng);
                }
            });
    }

    function updateAddressFields(latlng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('country').value = data.address.country || '';
                document.getElementById('city').value = data.address.city || data.address.town || '';
                document.getElementById('street').value = data.address.road || '';
            });
    }

    // Initialize map when step 2 is shown
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (document.querySelector('[data-step="2"].active') && !map) {
                initMap();
            }
        });
    });

    observer.observe(document.body, {
        attributes: true,
        subtree: true,
        attributeFilter: ['class']
    });

    $(document).ready(function () {
        let employees = [];
        let employeeIndex = 1; // Start idx from 1

        function updateEmployeesInput() {
            $('#employees-data').val(JSON.stringify(employees));
        }

        function addEmployeeRow(employee = {}) {
            let idx = employee.idx || employeeIndex++; // Assign existing idx or a new one
            let row = `
            <tr data-idx="${idx}">
                <td>${idx}</td>
                <td><input type="text" class="form-control full-name" value="${employee.full_name || ''}" required></td>
                <td><input type="text" class="form-control mobile" value="${employee.mobile || ''}" required></td>
                <td><input type="text" class="form-control role" value="${employee.role || ''}" required></td>
                <td><input type="text" class="form-control login-name" value="${employee.login_name || ''}" required></td>
                <td><input type="email" class="form-control email" value="${employee.email || ''}" required></td>
                <td><button type="button" class="btn btn-danger remove-employee">Remove</button></td>
            </tr>
        `;
            $('#employees-table tbody').append(row);
        }

        $('#add-employee').on('click', function () {
            addEmployeeRow();
        });

        $(document).on('click', '.remove-employee', function () {
            let idx = $(this).closest('tr').data('idx');
            employees = employees.filter(emp => emp.idx !== idx); // Remove from array
            $(this).closest('tr').remove();
            updateEmployeesInput();
        });

        function updateEmployees() {
            employees = [];
            $('#employees-table tbody tr').each(function () {
                let idx = $(this).data('idx');
                let employee = {
                    idx: idx,
                    full_name: $(this).find('.full-name').val(),
                    mobile: $(this).find('.mobile').val(),
                    role: $(this).find('.role').val(),
                    login_name: $(this).find('.login-name').val(),
                    email: $(this).find('.email').val(),
                };
                employees.push(employee);
            });
            updateEmployeesInput();
        }

        $(document).on('input', '#employees-table input', function () {
            updateEmployees();
        });
    });
});