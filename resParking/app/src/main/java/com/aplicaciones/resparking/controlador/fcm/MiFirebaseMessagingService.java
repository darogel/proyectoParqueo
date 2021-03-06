package com.aplicaciones.resparking.controlador.fcm;

import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.media.RingtoneManager;
import android.net.Uri;
import android.support.v4.app.NotificationCompat;
import android.util.Log;

import com.aplicaciones.resparking.MapsActivity;
import com.aplicaciones.resparking.R;
import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;

/**
 * Clase extendida de FirebaseMessagingService que maneja la informacion recibida de FCM
 * Mediante una variable TAG
 */
public class MiFirebaseMessagingService extends FirebaseMessagingService {
    /**
     * Variable TAG para la obtencion de datos enviados por FCM
     */
    public static final String TAG = "NOTICIAS";

    /**
     *Metodo sobreescrito para que obtener la informacion del mensaje enviado por FCM y presentado en el TAG
     * Ejecuta el metodo mostarNotificacion
     * @param remoteMessage
     */
    @Override
    public void onMessageReceived(RemoteMessage remoteMessage) {
        super.onMessageReceived(remoteMessage);

        String from = remoteMessage.getFrom();
        Log.d(TAG, "Mensaje recibido de: " + from);

        if (remoteMessage.getNotification() != null) {
            Log.d(TAG, "Notificación: " + remoteMessage.getNotification().getBody());

            mostrarNotificacion(remoteMessage.getNotification().getTitle(), remoteMessage.getNotification().getBody());
        }

        if (remoteMessage.getData().size() > 0) {
            Log.d(TAG, "Data: " + remoteMessage.getData());
        }

    }

    /**
     * Metodo implementado para creacion y presentacion de notificaciones
     * @param title titulo obtenido del response a la peticion enviada a FCM
     * @param body cuerpo obtenido del response a la peticion enviada a FCM
     */
    private void mostrarNotificacion(String title, String body) {

        Intent intent = new Intent(this, MapsActivity.class);
        intent.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        PendingIntent pendingIntent = PendingIntent.getActivity(this, 0, intent, PendingIntent.FLAG_ONE_SHOT);

        Uri soundUri = RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);

        NotificationCompat.Builder notificationBuilder = new NotificationCompat.Builder(this)
                .setSmallIcon(R.drawable.car)
                .setContentTitle(title)
                .setContentText(body)
                .setAutoCancel(true)
                .setSound(soundUri)
                .setContentIntent(pendingIntent);

        NotificationManager notificationManager = (NotificationManager) getSystemService(Context.NOTIFICATION_SERVICE);
        notificationManager.notify(0, notificationBuilder.build());

    }

}
