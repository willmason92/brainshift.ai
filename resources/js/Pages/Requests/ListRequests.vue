<template>
    <layout-default>
        <div class="filter-navigation">
            <Link href="/" class="request-detail__back button-reset">
                {{ $t('Home') }}
            </Link>

            <button
                @click="openPopup"
                class="filter-navigation__library-filters button button--small button--no-bg"
            >
                {{ $t('Filter') }}
            </button>
        </div>

        <div class="requests-title-bar">
            <h1 class="h1">{{ $t('Requests') }}</h1>
        </div>

        <div class="request-list__filters">
            <button
                class="active-filter pill button-reset"
                type="button"
                v-for="filter in activeFilter"
                @click="removeFilter(filter.id)"
            >
                <span class="active-filter__name">{{ filter.name }}</span
                ><svg
                    class="active-filter__icon"
                    width="12"
                    height="13"
                    viewBox="0 0 12 13"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M7.30141 6.49998L11.7814 1.99998C12.0214 1.75998 12.0214 1.37998 11.7814 1.15998C11.5414 0.91998 11.1614 0.91998 10.9414 1.15998L6.46141 5.65998L1.96141 1.15998C1.72141 0.91998 1.34141 0.91998 1.12141 1.15998C0.881406 1.39998 0.881406 1.77998 1.12141 1.99998L5.60141 6.49998L1.12141 11C0.881406 11.24 0.881406 11.62 1.12141 11.84C1.24141 11.96 1.40141 12.02 1.54141 12.02C1.68141 12.02 1.84141 11.96 1.96141 11.84L6.46141 7.33998L10.9614 11.84C11.0814 11.96 11.2414 12.02 11.3814 12.02C11.5214 12.02 11.6814 11.96 11.8014 11.84C12.0414 11.6 12.0414 11.22 11.8014 11L7.30141 6.49998Z"
                        fill="#010101"
                    ></path>
                </svg>
            </button>
        </div>

        <div v-if="!activeFilter.length">
            <h2 class="h4">New ({{ newRequestCount }})</h2>
            <div class="request-list">
                <Link
                    class="request request--full"
                    v-for="request in requests.filter(
                        (req) => req.request_status == 0
                    )"
                    :href="'requests/' + request.id + '/view'"
                >
                    <div class="request__icon">
                        <svg
                            width="40"
                            height="40"
                            viewBox="0 0 40 40"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M25.136 6.95595L35.8792 16.6416C35.9208 16.6779 35.954 16.7229 35.9766 16.7733C35.9992 16.8237 36.0107 16.8784 36.0101 16.9337V32.1957C36.0058 32.2969 35.9625 32.3926 35.8893 32.4627C35.8161 32.5327 35.7186 32.5718 35.6173 32.5717H3.7705V31.7861H8.17185L9.05145 24.1652L8.38 22.6443L4.39495 22.6208C4.32685 22.6206 4.26001 22.6024 4.2011 22.5683C4.1422 22.5341 4.09328 22.4851 4.05923 22.4261L3.48514 21.4189C3.43784 21.3405 3.41997 21.2478 3.43473 21.1574C3.44949 21.067 3.49592 20.9848 3.56571 20.9254L6.79538 18.0953L5.44913 16.6483C5.39642 16.5923 5.36132 16.522 5.34819 16.4462C5.33506 16.3704 5.34447 16.2924 5.37527 16.2219C5.40337 16.1497 5.45352 16.0881 5.5186 16.0459C5.58368 16.0037 5.66039 15.9831 5.73785 15.9869L9.06487 16.0071C10.0015 16.0541 10.6126 16.786 10.6461 17.8771H11.6735H23.1955C23.9216 17.8792 24.6277 18.1146 25.2099 18.5485H26.217C26.4405 18.5436 26.6628 18.5834 26.8707 18.6655C27.0787 18.7477 27.2681 18.8705 27.4279 19.0269C27.5877 19.1833 27.7147 19.37 27.8014 19.5761C27.888 19.7822 27.9326 20.0035 27.9326 20.2271V24.3565H27.1503V20.2271C27.1473 20.1076 27.1207 19.9899 27.0722 19.8807C27.0236 19.7715 26.954 19.6729 26.8673 19.5906C26.7807 19.5083 26.6786 19.4439 26.567 19.401C26.4554 19.3581 26.3365 19.3377 26.217 19.3408H25.9954C26.3956 19.91 26.6101 20.5889 26.6098 21.2847V24.041C26.6103 24.2038 26.5991 24.3665 26.5762 24.5278V31.7861H35.2379V17.1083L24.8741 7.77512L16.2359 15.3692L15.7189 14.7783L24.6156 6.95595C24.6874 6.89247 24.78 6.85742 24.8758 6.85742C24.9716 6.85742 25.0642 6.89247 25.136 6.95595ZM22.7053 27.4486H20.8051V31.7559H21.6008L22.7053 27.4486ZM14.7487 31.7861H20.0196V27.4486H15.259L14.7487 31.7861ZM23.8166 31.7861L23.1686 28.8015L22.4032 31.7861H23.8166ZM4.61653 21.8218L8.64522 21.8487C8.72117 21.8503 8.79493 21.8744 8.85711 21.9181C8.91928 21.9617 8.96707 22.0229 8.99437 22.0938L9.77661 23.8798H9.8639V24.0342L13.2211 31.7559H13.9631L14.5204 26.9953C14.5327 26.9013 14.5793 26.8151 14.6511 26.7532C14.7229 26.6913 14.815 26.658 14.9098 26.6596H23.1821C23.2727 26.6596 23.5648 26.6932 23.6084 27.0994L24.6156 31.7559H25.7907V24.4975C25.7892 24.4785 25.7892 24.4595 25.7907 24.4405C25.8107 24.3082 25.8208 24.1747 25.8209 24.041V21.2847C25.82 20.589 25.543 19.922 25.0507 19.4304C24.5585 18.9388 23.8912 18.6627 23.1955 18.6627H11.6735L10.438 18.6794C10.355 18.6735 10.274 18.6511 10.1998 18.6135C10.1255 18.5758 10.0596 18.5237 10.0058 18.4602C9.952 18.3967 9.91144 18.3231 9.8865 18.2437C9.86155 18.1643 9.85273 18.0808 9.86054 17.9979C9.86054 17.7327 9.80011 16.8598 9.02459 16.8195L6.63759 16.7927L7.64476 17.8703C7.68063 17.9085 7.70859 17.9533 7.72703 18.0023C7.74547 18.0513 7.75402 18.1034 7.75219 18.1557C7.74958 18.2083 7.73633 18.2598 7.71325 18.3071C7.69017 18.3544 7.65773 18.3966 7.6179 18.431L4.31438 21.3048L4.61653 21.8218ZM10.8677 28.3248L9.67253 25.5886L8.95073 31.7559H9.62218L10.8677 28.3248ZM12.3785 31.7861L11.3243 29.3689L10.438 31.7861H12.3785ZM27.1571 24.9401H27.9426V25.8969H27.1571V24.9401Z"
                                fill="#303135"
                            />
                        </svg>
                    </div>
                    <div class="request__details">
                        <div class="request-detail__tag tag--green">
                            {{ $t('New request') }}
                        </div>
                        <h3 class="request-detail__name">
                            {{ request?.user?.name || $t('Deleted user') }}
                        </h3>
                        <p
                            class="request-detail__location"
                            v-if="getLocation(request)"
                        >
                            {{ getLocation(request) }}
                        </p>
                    </div>
                    <div class="request__meta">
                        <div class="request-meta__date">
                            {{ formatDate(request.created_at) }}
                        </div>
                        <div class="request-meta__link">
                            <svg
                                width="30"
                                height="30"
                                viewBox="0 0 30 30"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M13.0511 6.09016L11.3711 7.80016L18.6611 14.9402L11.5211 22.2302L13.2611 23.9102L22.0511 14.8802L13.0511 6.09016Z"
                                    fill="#E60000"
                                />
                            </svg>
                        </div>
                    </div>
                </Link>
            </div>

            <h2 class="h4">Read ({{ readRequestCount }})</h2>
            <div class="request-list">
                <Link
                    class="request request--full"
                    v-for="request in requests.filter(
                        (req) => req.request_status !== 0
                    )"
                    :href="'requests/' + request.id + '/view'"
                >
                    <div class="request__icon">
                        <svg
                            width="40"
                            height="40"
                            viewBox="0 0 40 40"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M25.136 6.95595L35.8792 16.6416C35.9208 16.6779 35.954 16.7229 35.9766 16.7733C35.9992 16.8237 36.0107 16.8784 36.0101 16.9337V32.1957C36.0058 32.2969 35.9625 32.3926 35.8893 32.4627C35.8161 32.5327 35.7186 32.5718 35.6173 32.5717H3.7705V31.7861H8.17185L9.05145 24.1652L8.38 22.6443L4.39495 22.6208C4.32685 22.6206 4.26001 22.6024 4.2011 22.5683C4.1422 22.5341 4.09328 22.4851 4.05923 22.4261L3.48514 21.4189C3.43784 21.3405 3.41997 21.2478 3.43473 21.1574C3.44949 21.067 3.49592 20.9848 3.56571 20.9254L6.79538 18.0953L5.44913 16.6483C5.39642 16.5923 5.36132 16.522 5.34819 16.4462C5.33506 16.3704 5.34447 16.2924 5.37527 16.2219C5.40337 16.1497 5.45352 16.0881 5.5186 16.0459C5.58368 16.0037 5.66039 15.9831 5.73785 15.9869L9.06487 16.0071C10.0015 16.0541 10.6126 16.786 10.6461 17.8771H11.6735H23.1955C23.9216 17.8792 24.6277 18.1146 25.2099 18.5485H26.217C26.4405 18.5436 26.6628 18.5834 26.8707 18.6655C27.0787 18.7477 27.2681 18.8705 27.4279 19.0269C27.5877 19.1833 27.7147 19.37 27.8014 19.5761C27.888 19.7822 27.9326 20.0035 27.9326 20.2271V24.3565H27.1503V20.2271C27.1473 20.1076 27.1207 19.9899 27.0722 19.8807C27.0236 19.7715 26.954 19.6729 26.8673 19.5906C26.7807 19.5083 26.6786 19.4439 26.567 19.401C26.4554 19.3581 26.3365 19.3377 26.217 19.3408H25.9954C26.3956 19.91 26.6101 20.5889 26.6098 21.2847V24.041C26.6103 24.2038 26.5991 24.3665 26.5762 24.5278V31.7861H35.2379V17.1083L24.8741 7.77512L16.2359 15.3692L15.7189 14.7783L24.6156 6.95595C24.6874 6.89247 24.78 6.85742 24.8758 6.85742C24.9716 6.85742 25.0642 6.89247 25.136 6.95595ZM22.7053 27.4486H20.8051V31.7559H21.6008L22.7053 27.4486ZM14.7487 31.7861H20.0196V27.4486H15.259L14.7487 31.7861ZM23.8166 31.7861L23.1686 28.8015L22.4032 31.7861H23.8166ZM4.61653 21.8218L8.64522 21.8487C8.72117 21.8503 8.79493 21.8744 8.85711 21.9181C8.91928 21.9617 8.96707 22.0229 8.99437 22.0938L9.77661 23.8798H9.8639V24.0342L13.2211 31.7559H13.9631L14.5204 26.9953C14.5327 26.9013 14.5793 26.8151 14.6511 26.7532C14.7229 26.6913 14.815 26.658 14.9098 26.6596H23.1821C23.2727 26.6596 23.5648 26.6932 23.6084 27.0994L24.6156 31.7559H25.7907V24.4975C25.7892 24.4785 25.7892 24.4595 25.7907 24.4405C25.8107 24.3082 25.8208 24.1747 25.8209 24.041V21.2847C25.82 20.589 25.543 19.922 25.0507 19.4304C24.5585 18.9388 23.8912 18.6627 23.1955 18.6627H11.6735L10.438 18.6794C10.355 18.6735 10.274 18.6511 10.1998 18.6135C10.1255 18.5758 10.0596 18.5237 10.0058 18.4602C9.952 18.3967 9.91144 18.3231 9.8865 18.2437C9.86155 18.1643 9.85273 18.0808 9.86054 17.9979C9.86054 17.7327 9.80011 16.8598 9.02459 16.8195L6.63759 16.7927L7.64476 17.8703C7.68063 17.9085 7.70859 17.9533 7.72703 18.0023C7.74547 18.0513 7.75402 18.1034 7.75219 18.1557C7.74958 18.2083 7.73633 18.2598 7.71325 18.3071C7.69017 18.3544 7.65773 18.3966 7.6179 18.431L4.31438 21.3048L4.61653 21.8218ZM10.8677 28.3248L9.67253 25.5886L8.95073 31.7559H9.62218L10.8677 28.3248ZM12.3785 31.7861L11.3243 29.3689L10.438 31.7861H12.3785ZM27.1571 24.9401H27.9426V25.8969H27.1571V24.9401Z"
                                fill="#303135"
                            />
                        </svg>
                    </div>
                    <div class="request__details">
                        <div
                            class="request-detail__tag tag--green"
                            :class="getStatusClass(request.request_status)"
                        >
                            {{ getStatusText(request.request_status) }}
                        </div>
                        <h3 class="request-detail__name">
                            {{ request?.user?.name || $t('Deleted user') }}
                        </h3>
                        <p
                            class="request-detail__location"
                            v-if="getLocation(request)"
                        >
                            {{ getLocation(request) }}
                        </p>
                    </div>
                    <div class="request__meta">
                        <div class="request-meta__date">
                            {{ formatDate(request.created_at) }}
                        </div>
                        <div class="request-meta__link">
                            <svg
                                width="30"
                                height="30"
                                viewBox="0 0 30 30"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M13.0511 6.09016L11.3711 7.80016L18.6611 14.9402L11.5211 22.2302L13.2611 23.9102L22.0511 14.8802L13.0511 6.09016Z"
                                    fill="#E60000"
                                />
                            </svg>
                        </div>
                    </div>
                </Link>
            </div>
        </div>
        <div v-else>
            <div class="request-list">
                <Link
                    class="request request--full"
                    v-for="request in filterRequests()"
                    :href="'requests/' + request.id + '/view'"
                >
                    <div class="request__icon">
                        <svg
                            width="40"
                            height="40"
                            viewBox="0 0 40 40"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M25.136 6.95595L35.8792 16.6416C35.9208 16.6779 35.954 16.7229 35.9766 16.7733C35.9992 16.8237 36.0107 16.8784 36.0101 16.9337V32.1957C36.0058 32.2969 35.9625 32.3926 35.8893 32.4627C35.8161 32.5327 35.7186 32.5718 35.6173 32.5717H3.7705V31.7861H8.17185L9.05145 24.1652L8.38 22.6443L4.39495 22.6208C4.32685 22.6206 4.26001 22.6024 4.2011 22.5683C4.1422 22.5341 4.09328 22.4851 4.05923 22.4261L3.48514 21.4189C3.43784 21.3405 3.41997 21.2478 3.43473 21.1574C3.44949 21.067 3.49592 20.9848 3.56571 20.9254L6.79538 18.0953L5.44913 16.6483C5.39642 16.5923 5.36132 16.522 5.34819 16.4462C5.33506 16.3704 5.34447 16.2924 5.37527 16.2219C5.40337 16.1497 5.45352 16.0881 5.5186 16.0459C5.58368 16.0037 5.66039 15.9831 5.73785 15.9869L9.06487 16.0071C10.0015 16.0541 10.6126 16.786 10.6461 17.8771H11.6735H23.1955C23.9216 17.8792 24.6277 18.1146 25.2099 18.5485H26.217C26.4405 18.5436 26.6628 18.5834 26.8707 18.6655C27.0787 18.7477 27.2681 18.8705 27.4279 19.0269C27.5877 19.1833 27.7147 19.37 27.8014 19.5761C27.888 19.7822 27.9326 20.0035 27.9326 20.2271V24.3565H27.1503V20.2271C27.1473 20.1076 27.1207 19.9899 27.0722 19.8807C27.0236 19.7715 26.954 19.6729 26.8673 19.5906C26.7807 19.5083 26.6786 19.4439 26.567 19.401C26.4554 19.3581 26.3365 19.3377 26.217 19.3408H25.9954C26.3956 19.91 26.6101 20.5889 26.6098 21.2847V24.041C26.6103 24.2038 26.5991 24.3665 26.5762 24.5278V31.7861H35.2379V17.1083L24.8741 7.77512L16.2359 15.3692L15.7189 14.7783L24.6156 6.95595C24.6874 6.89247 24.78 6.85742 24.8758 6.85742C24.9716 6.85742 25.0642 6.89247 25.136 6.95595ZM22.7053 27.4486H20.8051V31.7559H21.6008L22.7053 27.4486ZM14.7487 31.7861H20.0196V27.4486H15.259L14.7487 31.7861ZM23.8166 31.7861L23.1686 28.8015L22.4032 31.7861H23.8166ZM4.61653 21.8218L8.64522 21.8487C8.72117 21.8503 8.79493 21.8744 8.85711 21.9181C8.91928 21.9617 8.96707 22.0229 8.99437 22.0938L9.77661 23.8798H9.8639V24.0342L13.2211 31.7559H13.9631L14.5204 26.9953C14.5327 26.9013 14.5793 26.8151 14.6511 26.7532C14.7229 26.6913 14.815 26.658 14.9098 26.6596H23.1821C23.2727 26.6596 23.5648 26.6932 23.6084 27.0994L24.6156 31.7559H25.7907V24.4975C25.7892 24.4785 25.7892 24.4595 25.7907 24.4405C25.8107 24.3082 25.8208 24.1747 25.8209 24.041V21.2847C25.82 20.589 25.543 19.922 25.0507 19.4304C24.5585 18.9388 23.8912 18.6627 23.1955 18.6627H11.6735L10.438 18.6794C10.355 18.6735 10.274 18.6511 10.1998 18.6135C10.1255 18.5758 10.0596 18.5237 10.0058 18.4602C9.952 18.3967 9.91144 18.3231 9.8865 18.2437C9.86155 18.1643 9.85273 18.0808 9.86054 17.9979C9.86054 17.7327 9.80011 16.8598 9.02459 16.8195L6.63759 16.7927L7.64476 17.8703C7.68063 17.9085 7.70859 17.9533 7.72703 18.0023C7.74547 18.0513 7.75402 18.1034 7.75219 18.1557C7.74958 18.2083 7.73633 18.2598 7.71325 18.3071C7.69017 18.3544 7.65773 18.3966 7.6179 18.431L4.31438 21.3048L4.61653 21.8218ZM10.8677 28.3248L9.67253 25.5886L8.95073 31.7559H9.62218L10.8677 28.3248ZM12.3785 31.7861L11.3243 29.3689L10.438 31.7861H12.3785ZM27.1571 24.9401H27.9426V25.8969H27.1571V24.9401Z"
                                fill="#303135"
                            />
                        </svg>
                    </div>
                    <div class="request__details">
                        <div
                            class="request-detail__tag tag--green"
                            :class="getStatusClass(request.request_status)"
                        >
                            {{ getStatusText(request.request_status) }}
                        </div>
                        <h3 class="request-detail__name">
                            {{ request?.user?.name || 'Deleted user' }}
                        </h3>
                        <p
                            class="request-detail__location"
                            v-if="getLocation(request)"
                        >
                            {{ getLocation(request) }}
                        </p>
                    </div>
                    <div class="request__meta">
                        <div class="request-meta__date">
                            {{ formatDate(request.created_at) }}
                        </div>
                        <div class="request-meta__link">
                            <svg
                                width="30"
                                height="30"
                                viewBox="0 0 30 30"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M13.0511 6.09016L11.3711 7.80016L18.6611 14.9402L11.5211 22.2302L13.2611 23.9102L22.0511 14.8802L13.0511 6.09016Z"
                                    fill="#E60000"
                                />
                            </svg>
                        </div>
                    </div>
                </Link>
            </div>
        </div>

        <Popup :active="popupActive" @active-state="closePopup">
            <RequestFilters
                :activeFilter="activeFilter"
                :filters="filters"
                :filterTitle="$t('Status')"
                @addFilter="addFilter"
                @clearFilters="resetFilters"
                @closePopup="closePopup"
            ></RequestFilters>
        </Popup>
    </layout-default>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3';
import LayoutDefault from '../../Layout/Base.vue';
import RequestFilters from './RequestFilters.vue';
import Popup from '../../Shared/Popup.vue';
import moment from 'moment';

export default {
    components: {
        LayoutDefault,
        Link,
        Popup,
        RequestFilters,
    },
    props: {
        requests: Array,
        newRequestCount: Number,
        readRequestCount: Number,
    },
    data() {
        return {
            popupActive: false,
            activeFilter: [],
            filters: [
                { id: 0, name: 'New request' },
                { id: 1, name: 'Contacted' },
                { id: 2, name: 'Converted' },
                { id: 3, name: 'Project finished' },
                { id: 4, name: 'Not converted' },
                { id: 5, name: 'Project failed' },
            ],
        };
    },
    methods: {
        formatDate(utc) {
            if (!utc) return 'Unknown';
            return moment(utc).format('DD/MM/yyyy');
        },
        getLocation(request) {
            if (!request?.user?.location) return false;
            let locationJson = JSON.parse(request.user.location.asJson);
            if (!locationJson?.city) return false;
            if (!locationJson?.country) return false;
            return `${locationJson.city}, ${locationJson.country}`;
        },
        openPopup() {
            this.popupActive = true;
        },
        closePopup() {
            this.popupActive = false;
        },
        addFilter(filter) {
            if (
                this.activeFilter.filter(
                    (activeFilter) => activeFilter.id == filter.id
                ).length
            ) {
                this.removeFilter(filter.id);
            } else {
                this.activeFilter.push({
                    id: filter.id,
                    name: filter.name,
                    category: filter.category,
                });
            }
        },
        removeFilter(id, deep = true) {
            // remove from active tag list
            this.activeFilter = this.activeFilter.filter(
                (entry) => entry.id != id
            );
        },
        resetFilters() {
            this.activeFilter = [];
        },
        getStatusText(id) {
            switch (id) {
                case 0:
                    return this.$t('New request');
                    break;
                case 1:
                    return this.$t('Contacted');
                    break;
                case 2:
                    return this.$t('Converted');
                    break;
                case 3:
                    return this.$t('Project finished');
                    break;
                case 4:
                    return this.$t('Not converted');
                    break;
                case 5:
                    return this.$t('Project failed');
                    break;
            }
        },
        getStatusClass(id) {
            switch (id) {
                case 0:
                    return '';
                    break;
                case 1:
                    return 'tag--contacted';
                    break;
                case 2:
                    return 'tag--converted';
                    break;
                case 3:
                    return 'tag--finished';
                    break;
                case 4:
                    return 'tag--finished';
                    break;
                case 5:
                    return 'tag--red';
                    break;
            }
        },
        filterRequests() {
            let filteredRequests = this.requests.filter((request) => {
                return this.activeFilter.filter(
                    (filter) => filter.id == request.request_status
                ).length;
            });
            return filteredRequests;
        },
    },
};
</script>
