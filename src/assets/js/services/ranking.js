import fetch from 'node-fetch';

const getRanking = () => {
    return fetch('/api/ranking')
        .then(response => response.json());
};

const getIndividualRanking = (playerId) => {
    return fetch('/api/ranking/5c36642979dab7965c7e5d23/' + playerId)
        .then(response => response.json());
};

export { getRanking, getIndividualRanking }
