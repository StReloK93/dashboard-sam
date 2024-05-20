export interface ISlides {
    start?: () => void;
    click?: () => void;
    timer?: Number;
    bgColor: String;
    value: Number;
    textColor: String;
    icon: String;
    class: String;
    component?: String;
    componentParams?: {
        width: Number;
        color: String;
        colorSecond: String;
        class: String;
    };
}
