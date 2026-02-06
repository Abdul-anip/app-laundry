<div x-data class="flex justify-end mb-2">
    <x-filament::button
        type="button"
        icon="heroicon-o-map-pin"
        @click="
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser');
                return;
            }

            $el.disabled = true;
            const originalText = $el.innerText;
            $el.innerText = 'Locating...';

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    $wire.set('data.laundry_latitude', position.coords.latitude);
                    $wire.set('data.laundry_longitude', position.coords.longitude);
                    
                    // Reverse Geocoding via Nominatim (OpenStreetMap)
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`)
                        .then(response => response.json())
                        .then(data => {
                            if(data.display_name) {
                                $wire.set('data.laundry_address', data.display_name);
                            }
                        });

                    new FilamentNotification()
                        .title('Location & Address updated')
                        .success()
                        .send();
                        
                    $el.disabled = false;
                    $el.innerText = originalText;
                },
                (error) => {
                    console.error(error);
                    alert('Unable to retrieve location: ' + error.message);
                    $el.disabled = false;
                    $el.innerText = originalText;
                }
            );
        "
    >
        Get Current Location
    </x-filament::button>
</div>
