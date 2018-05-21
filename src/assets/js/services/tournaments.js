import fetch from 'node-fetch';

const getPreviousTournaments = () => {
    return getTournaments(true);
};

const getFutureTournaments = () => {
    return getTournaments(false);
};

const getTournaments = (previous) => {
    return fetch('/api/tournaments?previous=' + previous)
        .then(response => response.json());
};

const getRank = (rank, type) => {
    if (rank === 'L') {
        return 'Lokalny';
    }

    if (rank === 'M') {
        if (type === 'single') {
            return 'Master indywidualny';
        }

        return 'Master drużynowy';
    }

    return '';
};

export { getFutureTournaments, getPreviousTournaments, getRank }
