function MissingDataException() {}

MissingDataException.prototype = Object.create(Error.prototype);
MissingDataException.prototype.constructor = MissingDataException;

export default MissingDataException;