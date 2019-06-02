import fetch from 'node-fetch';

const getRanking = (seasonId, army) => {
    const url = '/api/ranking' + (seasonId === undefined ? '' : '/' + seasonId ) + (army !== undefined && army !== '' ? '?army=' + army : '');

    return fetch(url)
        .then(response => response.json());
};

const getIndividualRanking = (playerId, seasonId) => {
    if (seasonId === undefined) {
        seasonId = '5c36642979dab7965c7e5d23';
    }
    return fetch('/api/ranking/' + seasonId + '/' + playerId)
        .then(response => response.json());
};

export { getRanking, getIndividualRanking }
