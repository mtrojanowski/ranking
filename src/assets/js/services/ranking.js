import fetch from 'node-fetch';

const getRanking = () => {
    return fetch('/api/ranking')
        .then(response => response.json());
};

const getIndividualRanking = (playerId) => {
    return fetch('/api/ranking/5b0d7c33fd0f9b6a4991169b/' + playerId)
        .then(response => response.json());
};

export { getRanking, getIndividualRanking }
