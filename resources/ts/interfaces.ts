export interface IPeriod {
   end: string;
   start: string;
   smena: number;
   day: string;
}

export interface ISmena {
   day: string;
   smena: number;
}

export interface IDaterange {
   startDay: string;
   endDay: string;
}

export interface IRemontTime {
   n_garaj: string;
   start_time: string | null;
   end_time: string | null;
   idCause: string;
   cause: string;
}

export interface IExcavator {
   id?: number | null;
   transport_id: number;
   career: string | null;
   career_id: number | null;
   geozone_id: number;
   last_message_time: string;
   group_id: number;
   volume: number;
   daily?: IExcavatorDaily;
   trucks_count: number;
   day_plan: number;
   remont_times: IRemontTime[];
   download_time: number;
   name: string;
   otval: any;
   shortname: number | null;
   type: string | null;
   with_geozone: boolean;
   loading?: boolean;
   trucks: ITruckState[] | any;
   in_smena: ITruckState[] | any;
   times: {
      workTime: number;
      freeTime: number;
      truckWaitTime: number;
      workload: number;
      summaRemontTimes: number;
   };
   isHidden: boolean;
}

export interface ITruckState {
   id?: number;
   causes: any[];
   distance: number | null;
   geozone: string;
   geozone_id: number;
   geozone_in: string;
   geozone_out: string;
   geozone_type: number;
   last_message_time: string;
   transport: any;
   timer: string | number;
   transport_id: number;
   timer_type: any;
   time: string;
}

export interface IExcavatorError {
   transport_id: number;
   day: string;
   smena: number;
   description: string | undefined | null;
   state_start: string;
   state_end: string;
   color: string;
}

export interface ISwapper {
   id?: number;
   organization_id?: number;
   day: string;
   smena: number;
   start: string;
   end: string;
   master: string;
   captain: string;
   operator: string;
}

export interface ILine {
   start: number;
   end: number;
   name: string;
   color: string;
   description?: string | null;
}

export interface IExcavatorDaily {
   id?: number;
   day: string;
   smena: number;
   transport_id: number;
   count: number | null;
   distance: number | null;
   loading_time?: number | null;
}

export interface IDumpTime {
   id?: number;
   day: string;
   smena: number;
   dump_id: number;
   stop_time?: number | null;
}

export interface IDumpDaily {
   id?: number;
   day: string;
   smena: number;
   dump_id: number;
   excavator_id: number;
   excavator: IExcavator;
}
export interface IDumpDailyList {
   id?: number;
   day: string;
   smena: number;
   dump_id: number;
   excavators: IExcavator[];
}

export type DataItem = {
   id: string;
   datetimeEnd: string;
   tripCount: string;
   qt: string;
   cube: number;
};

export type GroupedResult = {
   intervalStart: string;
   intervalEnd: string;
   y: number;
};

export interface IDump {
   id?: number;
   name: string;
   trucks: ITruckState[] | any;
   greyders: ITruckState[] | any;
   in_smena: ITruckState[] | any;
   daily: IDumpDaily[];
   redSeconds: number;
}
