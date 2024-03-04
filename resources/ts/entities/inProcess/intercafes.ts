export interface iInProcess{
    count: number,
    transports: iTransport[],
}

export interface iTransport{
    name: string,
    timer: number,
    distance: number,
    id: number
}