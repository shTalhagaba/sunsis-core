@push('after-scripts')
    <script>
        const loadingContainer = document.getElementById('loading-container');

        function fetchData(page = 1, incomingFilters = {}) {
            loadingContainer.style.display = 'block';

            if (Object.keys(incomingFilters).length > 0) {
                const formFilters = document.getElementById('frmFilters');

                Object.keys(incomingFilters).forEach((key) => {
                    const formElement = formFilters.elements[key];

                    if (formElement) {
                        formElement.value = incomingFilters[key];
                    }
                });
            }

            const formFiltersData = new FormData(document.getElementById('frmFilters'));
            formFiltersData.append('page', page);

            var filters = {};
            for (const pair of formFiltersData.entries()) {
                filters[pair[0]] = pair[1];
            }

            filters['product_id'] = 3; // Only Folio

            axios({
                    headers: {
                        'X-TokenID': `${TokenID}`
                    },
                    method: 'get',
                    url: 'https://{{ config('services.assistpro.base_uri') }}/api/v1/tickets',
                    // params: { page: page },
                    params: filters,
                })
                .then(function(response) {
                    // Update the table
                    updateTable(response.data.data);

                    // Update the pagination controls
                    updatePagination(response.data);

                    loadingContainer.style.display = 'none';
                })
                .catch(function(error) {
                    console.error(error);
                    loadingContainer.style.display = 'none';
                });
        }

        function handleFilterFormSubmit(event) {
            event.preventDefault();

            const filters = new FormData(event.target);

            fetchData(1, filters);
        }

        // Attach event listener to filter form
        const filterForm = document.getElementById('frmFilters');
        filterForm.addEventListener('submit', handleFilterFormSubmit);

        const priorityColors = {
            'Low': 'info',
            'Medium': 'info',
            'High': 'warning',
            'Critical': 'danger'
        };

        const statusColors = {
            'Assigned': 'info',
            'Awaiting Client': 'inverse',
            'Awaiting Confirmation': 'inverse',
            'Closed': 'success',
            'New': 'default',
            'Reopened': 'warning',
        };

        // Function to update the HTML table with the list of tickets
        function updateTable(ticketData) {
            const tbody = document.querySelector('#ticket-table tbody');
            tbody.innerHTML = '';

            ticketData.forEach(function(ticket) {
                const row = document.createElement('tr');
                row.onclick = function() {
                    // window.location.href = 'do.php?_action=view_support_ticket&id=' + ticket.id;
                    var url = '{{ route('tickets.show', ':ticket_id') }}';
                    url = url.replace(':ticket_id', ticket.id);
                    console.log(url);
                    window.location.href = url;
                };
                row.style.cursor = 'pointer';
                row.innerHTML = '<td>' + ticket.ticket_number + '</td>';
                row.innerHTML += '<td>' + ticket.subject + '</td>';
                row.innerHTML += '<td>' + ticket.account_contact.firstname + ' ' + ticket.account_contact.lastname +
                    '</td>';
                row.innerHTML += '<td>' + (ticket.description.length > 300 ? nl2br(ticket.description.substring(1, 300)) +
                    '...' : nl2br(ticket.description)) + '</td>';
                row.innerHTML += '<td><span class="badge badge-' + (ticket.status.description in statusColors ?
                        statusColors[ticket.status.description] : 'info') + '">' + ticket.status.description +
                    '</badge></td>';
                row.innerHTML += '<td><lable class="badge badge-' + priorityColors[ticket.priority.description] +
                    '">' + ticket.priority.description + '</badge></td>';
                row.innerHTML += '<td align="center">' + (ticket.resolved ? '<i class="fa fa-check-circle fa-2x green"></i>' : '') +
                    '</td>';
                row.innerHTML += '<td>' + (ticket.due_date != '' ? formatDate(ticket.due_date, false) : '') + '</td>';
                row.innerHTML += '<td>' + formatDate(ticket.created_at, true) + '</td>';
                row.innerHTML += '<td>' + formatDate(ticket.updated_at, true) + '</td>';

                tbody.appendChild(row);
            });
        }

        function resetDivPageSelector(paginationData) {
            const divPageSelector = document.querySelector("div#divPageSelector");
            divPageSelector.innerHTML = '';
            const pageSelector = document.createElement('select');
            pageSelector.setAttribute('id', 'pageSelector');
            divPageSelector.appendChild(pageSelector);

            pageSelector.innerHTML = '';
            for (var i = 1; i <= paginationData.meta.last_page; i++) {
                pageSelector.options.add(new Option(i, i));
            }
            pageSelector.value = paginationData.meta.current_page;
            pageSelector.removeEventListener('change', handlePageSelectorChange);
            pageSelector.addEventListener('change', function() {
                handlePageSelectorChange(pageSelector.value);
            });
        }

        function resetLeftNav(paginationData) {
            const leftTd = document.querySelector("td#leftTd");
            leftTd.innerHTML = '';

            const firstPageButton = document.createElement('button');
            firstPageButton.innerHTML = '<i class="fa fa-step-backward"></i>';
            firstPageButton.classList.add('btn', 'btn-sm', 'btn-white', 'btn-info', 'btn-round', 'margin-r-5');
            firstPageButton.setAttribute('id', 'firstPage');
            leftTd.appendChild(firstPageButton);
            firstPageButton.removeEventListener('click', handleFirstPageButtonClick);
            firstPageButton.addEventListener('click', function() {
                handleFirstPageButtonClick(paginationData);
            });
            firstPageButton.disabled = true;
            if (paginationData.meta.current_page > 1) {
                firstPageButton.disabled = false;
            }

            const prevPageButton = document.createElement('button');
            prevPageButton.innerHTML = '<i class="fa fa-caret-left"></i>';
            prevPageButton.classList.add('btn', 'btn-sm', 'btn-white', 'btn-info', 'btn-round');
            prevPageButton.setAttribute('id', 'prevPage');
            leftTd.appendChild(prevPageButton);
            prevPageButton.disabled = true;
            if (paginationData.links.prev) {
                prevPageButton.disabled = false;
                prevPageButton.removeEventListener('click', handlePrevButtonClick);
                prevPageButton.addEventListener('click', function() {
                    handlePrevButtonClick(paginationData);
                });
            }
        }

        function resetRightNav(paginationData) {
            const rightTd = document.querySelector("td#rightTd");
            rightTd.innerHTML = '';

            const nextPageButton = document.createElement('button');
            nextPageButton.innerHTML = '<i class="fa fa-caret-right"></i>';
            nextPageButton.classList.add('btn', 'btn-sm', 'btn-white', 'btn-info', 'btn-round', 'margin-r-5');
            nextPageButton.setAttribute('id', 'nextPage');
            rightTd.appendChild(nextPageButton);
            nextPageButton.disabled = true;
            if (paginationData.links.next) {
                nextPageButton.disabled = false;
                nextPageButton.removeEventListener('click', handleNextButtonClick);
                nextPageButton.addEventListener('click', function() {
                    handleNextButtonClick(paginationData);
                });
            }

            const lastPageButton = document.createElement('button');
            lastPageButton.innerHTML = '<i class="fa fa-step-forward"></i>';
            lastPageButton.classList.add('btn', 'btn-sm', 'btn-white', 'btn-info', 'btn-round');
            lastPageButton.setAttribute('id', 'lastPage');
            rightTd.appendChild(lastPageButton);
            lastPageButton.disabled = true;
            if (paginationData.meta.current_page != paginationData.meta.last_page) {
                lastPageButton.disabled = false;
                lastPageButton.removeEventListener('click', handleLastPageButtonClick);
                lastPageButton.addEventListener('click', function() {
                    handleLastPageButtonClick(paginationData);
                });
            }
        }

        // Function to update the pagination controls
        function updatePagination(paginationData) {
            resetDivPageSelector(paginationData);
            resetLeftNav(paginationData);
            resetRightNav(paginationData);

            const lastPageNumber = document.querySelector('span#lastPageNumber');
            const totalRecords = document.querySelector('span#totalRecords');

            lastPageNumber.innerText = paginationData.meta.last_page;
            totalRecords.innerText = paginationData.meta.total;
        }

        function handlePageSelectorChange(page) {
            fetchData(page);
        }

        function handleFirstPageButtonClick(paginationData) {
            fetchData(1);
        }

        function handlePrevButtonClick(paginationData) {
            fetchData(paginationData.meta.current_page - 1);
        }

        function handleNextButtonClick(paginationData) {
            fetchData(paginationData.meta.current_page + 1);
        }

        function handleLastPageButtonClick(paginationData) {
            fetchData(paginationData.meta.last_page);
        }


        if (window.requestFilters != '' && window.requestFilters != '[]') {
            requestFilters = $.parseJSON(requestFilters);
            fetchData(1, requestFilters);
        } else {
            fetchData();
        }

        function nl2br (str, is_xhtml) {
            if (typeof str === 'undefined' || str === null) {
                return '';
            }
            var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
            return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
        }
    </script>
@endpush
