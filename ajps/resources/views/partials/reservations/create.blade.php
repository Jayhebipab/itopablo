<div class="bg-gray-100 min-h-screen font-sans">
    <div class="p-8 w-full">
          <h1 class="text-2xl font-semibold flex items-center gap-2 mb-4">
    ➕ Reservation List
  </h1>

        <!-- Search Bar Section -->
        <div class="bg-white p-4 rounded-lg shadow mb-6 flex items-center gap-2">
            <input
                id="searchInput"
                type="text"
                placeholder="Search by phone number, name, or email..."
                class="flex-1 border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
            />
            <button id="searchButton" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-1 transition">
                🔍 Search
            </button>
            <button id="resetButton" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg flex items-center gap-1 transition">
                ❌ Reset
            </button>
        </div>
        
        <!-- Reservation List Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden border-t-4 border-gray-800">
            <div class="bg-gray-800 text-white p-4 flex items-center justify-between">
                <h2 class="text-lg font-semibold">Reservation List</h2>
                <div id="resultsCount" class="bg-blue-600 px-3 py-1 rounded-full text-xs font-bold hidden">
                    <span id="countSpan"></span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer Information</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reservation Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instruction</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="reservationsTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Loop through the reservations data -->
                        @foreach ($reservations as $reservation)
                        <tr class="hover:bg-gray-50 transition"
                            data-name="{{ strtolower($reservation->first_name) }} {{ strtolower($reservation->last_name) }}"
                            data-email="{{ strtolower($reservation->email) }}"
                            data-phone="{{ $reservation->phone }}"
                            onclick="showReservationDetails({{ json_encode($reservation) }})">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                #{{ $reservation->id }}
                            </td>


                            <td class="px-4 py-3">
                            <div class="font-semibold">{{ $reservation->first_name }} {{ $reservation->last_name  }}</div>
                            <div class="flex items-center gap-1 text-gray-600 text-xs">
                            📧 {{ $reservation->email }}
            </div>
            <div class="flex items-center gap-1 text-gray-600 text-xs">
              📞 {{ $reservation->phone }}
            </div>
          </td>

                                      <td class="px-4 py-3 text-sm">
                    <div class="flex items-center gap-1">
              📅 <strong>Service:</strong> {{ $reservation->service  }}
            </div>
          </td>
                                      <td class="px-4 py-3 text-sm italic text-gray-500">
            <div class="flex items-center gap-1">
              📅 <strong>Date:</strong> {{ \Carbon\Carbon::parse($reservation->preferred_date)->format('M d, Y') }}
            </div>
            <div class="flex items-center gap-1">
              🕒 <strong>Time:</strong> {{ date('h:i A', strtotime($reservation->preferred_time)) }}
            </div>
          </td>          
          <td class="px-4 py-3">
            <div class="text-sm italic text-gray-500 max-w-xs truncate overflow-hidden">
              {{ $reservation->instruction }}
            </div>
          <td class="px-4 py-3">
            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-xs font-medium">
              ● Approved{{ $reservation->status }}
            </span>
          </td>

<td class="px-4 py-3 space-y-2">
    <form action="">
        @csrf
        <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs w-full">
           Finish
        </button>
    </form>
    <form action="">
        @csrf
        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs w-full">
            Cancel
        </button>
    </form>
</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Reservation Details -->
<div id="reservationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-lg">
        <div class="flex justify-between items-center border-b pb-3 mb-4">
            <h2 class="text-xl font-bold text-gray-800">Reservation Details</h2>
            <button onclick="hideReservationModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>
        <div id="modalContent" class="space-y-4 text-gray-700">
        </div>
        <div class="mt-6 flex justify-end space-x-3">
            <button onclick="hideReservationModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg transition">Close</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const resetButton = document.getElementById('resetButton');
        const tableBody = document.getElementById('reservationsTableBody');
        const rows = tableBody.getElementsByTagName('tr');
        const resultsCountDiv = document.getElementById('resultsCount');
        const countSpan = document.getElementById('countSpan');

        // Function to filter reservations based on search input
        function filterReservations() {
            const searchText = searchInput.value.toLowerCase();
            let visibleCount = 0;

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const name = row.getAttribute('data-name');
                const email = row.getAttribute('data-email');
                const phone = row.getAttribute('data-phone');
                
                if (name.includes(searchText) || email.includes(searchText) || phone.includes(searchText)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            }
            
            // Show or hide the number of results found
            if (searchText.length > 0) {
                resultsCountDiv.classList.remove('hidden');
                countSpan.textContent = visibleCount + ' Requests Found';
            } else {
                resultsCountDiv.classList.add('hidden');
            }
        }

        // Add event listeners
        searchButton.addEventListener('click', filterReservations);
        searchInput.addEventListener('keyup', function(event) {
            if (event.key === 'Enter') {
                filterReservations();
            } else {
                 // Live search as user types
                filterReservations();
            }
        });

        resetButton.addEventListener('click', function() {
            searchInput.value = '';
            filterReservations();
        });
    });

    function showReservationDetails(reservation) {
        const modal = document.getElementById('reservationModal');
        const modalContent = document.getElementById('modalContent');
        
        // Populate modal content with reservation details
        modalContent.innerHTML = `
            <p><strong>ID:</strong> #${reservation.id}</p>
            <p><strong>Name:</strong> ${reservation.first_name} ${reservation.last_name}</p>
            <p><strong>Email:</strong> ${reservation.email}</p>
            <p><strong>Phone:</strong> ${reservation.phone}</p>
            <p><strong>Service:</strong> ${reservation.service}</p>
            <p><strong>Date:</strong> ${new Date(reservation.preferred_date).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })}</p>
            <p><strong>Time:</strong> ${reservation.preferred_time}</p>
            <p><strong>Instruction:</strong></p>
            <pre class="bg-gray-100 p-3 rounded-lg whitespace-pre-wrap overflow-auto max-h-40">${reservation.instruction}</pre>
            <p><strong>Status:</strong> ${reservation.status}</p>
        `;
        
        // Show modal
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function hideReservationModal() {
        const modal = document.getElementById('reservationModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === "Escape") hideReservationModal();
    });
</script>
