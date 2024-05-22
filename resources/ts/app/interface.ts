import { ComputedRef } from "vue"

interface ISlidesBase {
    onStart?: () => void;
    onClick?: () => void;
    timer?: number;
    class?: string;
    bgColor: string;
    type: string;
    value: ComputedRef<number>;
    textColor: string;
}

interface ISlideWithIcon extends ISlidesBase {
    icon: string;
    component?: never;
    componentParams?: never;
}

interface ISlideWithComponent extends ISlidesBase {
    icon?: never;
    component: string;
    componentParams: any;
}

export type ISlide = ISlideWithIcon | ISlideWithComponent;