package com.aplicaciones.resparking.controlador.adaptador;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import com.aplicaciones.resparking.R;
import com.aplicaciones.resparking.modelo.Plaza;
import com.aplicaciones.resparking.modelo.Vehiculo;

import java.util.ArrayList;
import java.util.List;

public class ListaPlaza extends ArrayAdapter<Plaza> {
    private List<Plaza> dataSet;
    Context mContext;

    public ListaPlaza(List<Plaza> data, Context context){
        super(context, R.layout.item_lista,data); //ojo con item_lista
        this.dataSet=data;
        this.mContext=context;
    }

    public ListaPlaza(Context context){
        super(context, R.layout.lista_vacia, new ArrayList<Plaza>());

        this.dataSet = new ArrayList<Plaza>();
        this.mContext = context;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        LayoutInflater inflater = LayoutInflater.from(mContext);
        View item = null;
        if (dataSet.isEmpty()){
            item=inflater.inflate(R.layout.lista_vacia,null);
        }else {
            item=inflater.inflate(R.layout.item_lista,null);
        }


        /*/TextView numeroP=(TextView)item.findViewById(R.id.vPlacaD);
        numeroP.setText(dataSet.get(position).numero_puesto);*/

        return item;
    }
}