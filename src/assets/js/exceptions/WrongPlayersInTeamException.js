function WrongPlayersInTeamException() {}

WrongPlayersInTeamException.prototype = Object.create(Error.prototype);
WrongPlayersInTeamException.prototype.constructor = WrongPlayersInTeamException;

export default WrongPlayersInTeamException;
