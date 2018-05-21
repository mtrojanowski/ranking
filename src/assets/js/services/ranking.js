import fetch from 'node-fetch';

const getRanking = () => {
    return fetch('/api/ranking')
        .then(response => response.json());
};

export { getRanking }
