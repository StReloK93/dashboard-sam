import { AxiosStatic } from "axios"
import Echo from "laravel-echo"
declare global {
    var axios: AxiosStatic;
    var wialon: any;
    var L: any;
    var echo: Echo;
}