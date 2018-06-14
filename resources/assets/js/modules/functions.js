
export const randomInt = (min, max) => Match.floor(Match.random() * (max - min)) + min;
